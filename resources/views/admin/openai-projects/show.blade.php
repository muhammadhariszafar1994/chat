<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('admin/project.details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-2xl">
                    <!-- Project ID -->
                    <div>
                        <x-input-label for="id" :value="__('global.id')" />
                        <p class="mt-1 text-gray-700">{{ $project['id'] }}</p>
                    </div>

                    <!-- Project Name -->
                    <div class="mt-4">
                        <x-input-label for="name" :value="__('admin/project.attributes.name')" />
                        <p class="mt-1 text-gray-700">{{ $project['name'] }}</p>
                    </div>

                    <!-- Created At -->
                    <div class="mt-4">
                        <x-input-label for="created_at" :value="__('global.created_at')" />
                        <p class="mt-1 text-gray-700">{{ \Carbon\Carbon::createFromTimestamp($project['created_at'])->format('d/m/Y H:i') }}</p>
                    </div>

                    <!-- Status -->
                    <div class="mt-4">
                        <x-input-label for="status" :value="__('global.status')" />
                        <p class="mt-1 text-gray-700">{{ $project['status'] }}</p>
                    </div>

                    <!-- Archived At (if available) -->
                    @if ($project['archived_at'])
                        <div class="mt-4">
                            <x-input-label for="archived_at" :value="__('admin/global.archived_at')" />
                            <p class="mt-1 text-gray-700">{{ \Carbon\Carbon::createFromTimestamp($project['archived_at'])->format('d/m/Y H:i') }}</p>
                        </div>
                    @endif

                    <!-- Actions Section (Back and Edit) -->
                    <div class="mt-4 flex gap-4">
                        <a href="{{ route('admin.openai-projects.index') }}">
                            <x-secondary-button>{{ __('global.back') }}</x-secondary-button>
                        </a>
                        @can('project_edit')
                            <a href="{{ route('admin.openai-projects.edit', $project['id']) }}">
                                <x-primary-button>{{ __('global.edit') }}</x-primary-button>
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
