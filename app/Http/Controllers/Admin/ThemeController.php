<?php

namespace App\Http\Controllers\Admin;

use App\Models\Theme;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Theme\ThemeStoreRequest;
use App\Http\Requests\Admin\Theme\ThemeUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ThemeController extends Controller
{
    /**
     * Display all themes.
     */
    public function index(Request $request): View
    {
        abort_if(Gate::denies('theme_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sortableColumns = ['id', 'name', 'is_active', 'created_at'];
        $themes = Theme::query();

        if ($request->filled('sort') && in_array($request->get('sort'), $sortableColumns)) {
            $themes->orderBy($request->get('sort'), $request->get('direction', 'asc'));
        } else {
            $themes->latest();
        }

        $themes = $themes->paginate(10);

        return view('admin.themes.index', compact('themes'));
    }

    /**
     * Show form to create a new theme.
     */
    public function create(): View
    {
        abort_if(Gate::denies('theme_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.themes.create');
    }

    /**
     * Store a new theme.
     */
    public function store(ThemeStoreRequest $request): RedirectResponse
    {
        abort_if(Gate::denies('theme_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Only one theme can be active at a time
        if ($request->boolean('is_active')) {
            Theme::where('is_active', true)->update(['is_active' => false]);
        }

        $theme = Theme::create($request->validated());

        // Handle media uploads
        if ($request->hasFile('chat_button_image')) {
            $theme->addMediaFromRequest('chat_button_image')
                ->toMediaCollection('chat_button_image');
        }

        if ($request->hasFile('chat_toggle_image')) {
            $theme->addMediaFromRequest('chat_toggle_image')
                ->toMediaCollection('chat_toggle_image');
        }

        return redirect()
            ->route('admin.themes.index')
            ->with('success', 'Theme created successfully.');
    }

    /**
     * Show form to edit a theme.
     */
    public function edit(Theme $theme): View
    {
        abort_if(Gate::denies('theme_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.themes.edit', compact('theme'));
    }

    /**
     * Update the given theme.
     */
    public function update(ThemeUpdateRequest $request, Theme $theme): RedirectResponse
    {
        abort_if(Gate::denies('theme_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Only one theme can be active at a time
        if ($request->boolean('is_active')) {
            Theme::where('is_active', true)
                ->where('id', '!=', $theme->id)
                ->update(['is_active' => false]);
        }

        $theme->update($request->validated());

        // Handle media updates
        if ($request->hasFile('chat_button_image')) {
            $theme->clearMediaCollection('chat_button_image');
            $theme->addMediaFromRequest('chat_button_image')
                ->toMediaCollection('chat_button_image');
        }

        if ($request->hasFile('chat_toggle_image')) {
            $theme->clearMediaCollection('chat_toggle_image');
            $theme->addMediaFromRequest('chat_toggle_image')
                ->toMediaCollection('chat_toggle_image');
        }

        return redirect()
            ->route('admin.themes.index')
            ->with('success', 'Theme updated successfully.');
    }

    /**
     * Show the given theme.
     */
    public function show(Theme $theme): View
    {
        abort_if(Gate::denies('theme_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.themes.show', compact('theme'));
    }

    /**
     * Delete a theme.
     */
    public function destroy(Theme $theme): RedirectResponse
    {
        abort_if(Gate::denies('theme_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Delete related media first
        $theme->clearMediaCollection('chat_button_image');
        $theme->clearMediaCollection('chat_toggle_image');

        // Then delete theme
        $theme->delete();

        return redirect()
            ->route('admin.themes.index')
            ->with('success', 'Theme deleted successfully.');
    }
}
