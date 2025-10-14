<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class OpenAIAdminController extends Controller
{
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    /**
     * Display a listing of OpenAI organization projects.
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('openai_project_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $after = $request->get('after');
        $limit = $request->get('limit', 20);
        $includeArchived = $request->get('include_archived', false);

        $projects = $this->openAIService->listProjects($after, $limit, $includeArchived);

        $projectsData = collect($projects['data']);
        
        $currentPage = $request->get('page', 1);
        $total = count($projectsData);
        $projectsPaginated = new LengthAwarePaginator(
            $projectsData->forPage($currentPage, $limit),
            $total,
            $limit,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );

        $projectsPaginated->setCollection($projectsPaginated->getCollection());

        return view('admin.openai-projects.index', compact('projectsPaginated'));
    }




    /**
     * Show the form to create a new OpenAI project.
     */
    public function create()
    {
        abort_if(Gate::denies('openai_project_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.openai-projects.create');
    }

    /**
     * Store a new OpenAI project via OpenAI API.
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('openai_project_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $result = $this->openAIService->createProject($request->input('name'));

        if (!$result) {
            return redirect()->back()->with('error', 'Failed to create project in OpenAI.');
        }

        return redirect()->route('admin.openai-projects.index')
                         ->with('success', 'OpenAI project created successfully.');
    }

    /**
     * Show details of the specified OpenAI project.
     */
    public function show($projectId)
    {
        abort_if(Gate::denies('openai_project_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Fetch project details using OpenAI service
        $project = $this->openAIService->getProjectDetails($projectId);

        if (!$project) {
            return redirect()->route('admin.openai-projects.index')->with('error', 'Project not found.');
        }

        return view('admin.openai-projects.show', compact('project'));
    }

    /**
     * Archive the specified OpenAI project.
     */
    public function archive($projectId)
    {
        abort_if(Gate::denies('openai_project_archive'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $result = $this->openAIService->archiveProject($projectId);

        if (!$result) {
            return redirect()->back()->with('error', 'Failed to archive the project.');
        }

        return redirect()->route('admin.openai-projects.index')
                         ->with('success', 'Project archived successfully.');
    }

    /**
     * Show the form to edit the specified OpenAI project.
     */
    public function edit($projectId)
    {
        abort_if(Gate::denies('openai_project_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Fetch project for editing
        $project = $this->openAIService->getProjectDetails($projectId);

        if (!$project) {
            return redirect()->route('admin.openai-projects.index')->with('error', 'Project not found.');
        }

        return view('admin.openai-projects.edit', compact('project'));
    }

    /**
     * Update the specified OpenAI project.
     */
    public function update(Request $request, $projectId)
    {
        abort_if(Gate::denies('openai_project_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Update the project using OpenAI service
        $result = $this->openAIService->updateProject($projectId, $request->input('name'));

        if (!$result) {
            return redirect()->back()->with('error', 'Failed to update the project.');
        }

        return redirect()->route('admin.openai-projects.index')
                         ->with('success', 'Project updated successfully.');
    }

    /**
     * Delete the specified OpenAI project.
     */
    public function destroy($projectId)
    {
        abort_if(Gate::denies('openai_project_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Delete the project via OpenAI service
        $result = $this->openAIService->deleteProject($projectId);

        if (!$result) {
            return redirect()->back()->with('error', 'Failed to delete the project.');
        }

        return redirect()->route('admin.openai-projects.index')
                         ->with('success', 'Project deleted successfully.');
    }
}
