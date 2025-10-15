<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Project\ProjectStoreRequest;
use App\Http\Requests\Admin\Project\ProjectUpdateRequest;
use App\Models\Project;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use App\Services\OpenAIService;

class ProjectController extends Controller
{
    protected $openAIService;

    public function __construct(
        OpenAIService $openAIService
    )
    {
        $this->openAIService = $openAIService;
    }

    /**
     * Display a listing of projects.
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('project_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sortableColumns = ['id', 'name', 'created_at'];
        $projects = Project::query();

        if ($request->filled('sort') && in_array($request->get('sort'), $sortableColumns)) {
            $projects->orderBy($request->get('sort'), $request->get('direction', 'asc'));
        } else {
            $projects->latest();
        }

        $projects = $projects->paginate(10);

        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        abort_if(Gate::denies('project_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $themes = Theme::all();
        $users = User::all();
        $apiKeys = [];

        $openAIProjects = $this->openAIService->listProjects();

        $projects = collect($openAIProjects['data'] ?? [])
            ->pluck('name', 'id')
            ->toArray();

        return view('admin.projects.create', compact('themes', 'users', 'projects', 'apiKeys'));
    }

    /**
     * Store a newly created project.
     */
    public function store(ProjectStoreRequest $request)
    {
        abort_if(Gate::denies('project_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validated();
        $data['token'] = Str::uuid(); // Automatically generate token

        // Build script tag using APP_URL
        $baseUrl = rtrim(config('app.url'), '/'); // ensures no trailing slash
        $script = '<script async src="' . $baseUrl . '/embed.js?token=' . $data['token'] . '"></script>';
        $data['script'] = $script;

        Project::create($data);

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project)
    {
        abort_if(Gate::denies('project_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(Project $project)
    {
        abort_if(Gate::denies('project_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $themes = Theme::all();
        $users = User::all();
        $apiKeys = [];

        $openAIProjects = $this->openAIService->listProjects();

        $projects = collect($openAIProjects['data'] ?? [])
            ->pluck('name', 'id')
            ->toArray();

        return view('admin.projects.edit', compact('project', 'themes', 'users', 'projects', 'apiKeys'));
    }

    /**
     * Update the specified project.
     */
    public function update(ProjectUpdateRequest $request, Project $project)
    {
        abort_if(Gate::denies('project_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validated();
        // Do NOT update token here (it should remain the same)
        unset($data['token']);

        $project->update($data);

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Project $project)
    {
        abort_if(Gate::denies('project_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $project->delete();

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    /**
     * Get OpenAI API keys for a given project ID.
     */
    public function projectApiKeys(Request $request)
    {
        $projectId = $request->get('project_id');

        if (!$projectId) {
            return response()->json(['message' => 'Project ID is required'], 400);
        }

        $after = $request->get('after');
        $limit = $request->get('limit', 20);

        $apiKeysData = $this->openAIService->getProjectApiKeys($projectId, $after, $limit);

        if (!$apiKeysData) {
            return response()->json(['message' => 'Failed to fetch API keys'], 500);
        }

        $keys = collect($apiKeysData['data'] ?? [])
            ->pluck('id', 'name')
            ->toArray();

        return response()->json($keys);
    }

}
