<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('admin/project.title_plural') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-table
                :create="auth()->user()->can('openai_project_create') ? ['url' => route('admin.openai-projects.create'), 'label' => __('admin/project.create')] : null"
                :headers="['id', 'name', 'created_at', 'actions']"
                :labels="[
                    'ID',
                    __('admin/project.attributes.name'),
                    __('global.created_at'),
                    __('global.actions')
                ]"
                :data="collect($projectsPaginated->items())->map(function($project) {
                    return [
                        'id' => $project['id'],
                        'name' => e($project['name']),
                        'created_at' => \Carbon\Carbon::parse($project['created_at'])->format('d/m/Y H:i'),
                        'actions' => collect([
                            auth()->user()->can('openai_project_view') ? [
                                'name' => 'show',
                                'url' => route('admin.openai-projects.show', $project['id']),
                                'label' => __('global.details'),
                                'color' => 'green'
                            ] : null,
                            auth()->user()->can('openai_project_edit') ? [
                                'name' => 'edit',
                                'url' => route('admin.openai-projects.edit', $project['id']),
                                'label' => __('global.edit'),
                                'color' => 'blue'
                            ] : null,
                            auth()->user()->can('openai_project_archive') ? [
                                'name' => 'archive',
                                'form' => [
                                    'method' => 'POST',
                                    'action' => route('admin.openai-projects.archive', $project['id']),
                                    'button_label' => __('global.archive'),
                                    'color' => 'yellow',
                                ]
                            ] : null,
                        ])->filter()->toArray(),
                    ];
                })"
                :pagination="$projectsPaginated->links()"
            />
        </div>
    </div>
</x-admin-layout>
