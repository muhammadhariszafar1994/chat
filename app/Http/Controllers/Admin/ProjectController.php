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

class ProjectController extends Controller
{
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

        return view('admin.projects.create', compact('themes', 'users'));
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

        return view('admin.projects.edit', compact('project', 'themes', 'users'));
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
}
