<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('admin/project.title_plural') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-table
                :create="auth()->user()->can('project_create') ? ['url' => route('admin.projects.create'), 'label' => __('admin/project.create')] : null"
                :headers="['id', 'name', 'user', 'theme', 'client_url', 'created_at']"
                :labels="[
                    'ID',
                    __('admin/project.attributes.name'),
                    __('admin/project.attributes.user_id'),
                    __('admin/project.attributes.theme_id'),
                    __('admin/project.attributes.client_url'),
                    __('global.created_at'),
                    __('global.actions')
                ]"
                :data="$projects->map(function($project) {
                    return [
                        'id' => $project->id,
                        'name' => e($project->name),
                        'user' => $project->user ? e($project->user->name) : '-',
                        'theme' => $project->theme ? e($project->theme->name) : '-',
                        'client_url' => $project->client_url ? '<a href=\'' . e($project->client_url) . '\' target=\'_blank\' class=\'text-blue-600 underline\'>' . e($project->client_url) . '</a>' : '-',
                        'created_at' => $project->created_at->format('d/m/Y H:i'),
                        'actions' => collect([
                            auth()->user()->can('project_view') ? [
                                'name' => 'show',
                                'url' => route('admin.projects.show', $project->id),
                                'label' => __('global.details'),
                                'color' => 'green'
                            ] : null,
                            auth()->user()->can('project_edit') ? [
                                'name' => 'edit',
                                'url' => route('admin.projects.edit', $project->id),
                                'label' => __('global.edit'),
                                'color' => 'blue'
                            ] : null,
                            auth()->user()->can('project_delete') ? [
                                'name' => 'delete',
                                'url' => route('admin.projects.destroy', $project->id),
                                'label' => __('global.delete'),
                            ] : null,
                        ])->filter()->toArray(),
                    ];
                })"
                :pagination="$projects->links()"
            />
        </div>
    </div>
</x-admin-layout>
