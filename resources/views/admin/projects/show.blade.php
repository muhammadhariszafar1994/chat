<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('admin/project.show') ?? 'Project Details' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                
                {{-- Header with back button --}}
                <div class="p-4 flex items-center justify-between border-b">
                    <div class="text-lg font-medium text-gray-700">
                        {{ $project->name }}
                    </div>
                    <div>
                        <a href="{{ route('admin.projects.index') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-sm rounded-md hover:bg-gray-200">
                            {{ __('global.back') }}
                        </a>
                    </div>
                </div>

                <div class="p-6 space-y-6">

                    {{-- General Info --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-3">General</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <div class="text-sm text-gray-500">Name</div>
                                <div class="mt-1 text-sm text-gray-800">{{ $project->name ?? '—' }}</div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-500">Client URL</div>
                                <div class="mt-1 text-sm text-gray-800">
                                    @if($project->client_url)
                                        <a href="{{ $project->client_url }}" target="_blank" class="text-blue-600 hover:underline">{{ $project->client_url }}</a>
                                    @else
                                        —
                                    @endif
                                </div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-500">Theme</div>
                                <div class="mt-1 text-sm text-gray-800">{{ $project->theme?->name ?? '—' }}</div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-500">User</div>
                                <div class="mt-1 text-sm text-gray-800">{{ $project->user?->name ?? '—' }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Script --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-3">Script</h3>
                        <div class="p-3 border rounded bg-gray-50">
                            <pre class="text-sm text-gray-800">{{ $project->script ?? '—' }}</pre>
                        </div>
                    </div>

                    {{-- OpenAI --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-3">OpenAI Settings</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <div class="text-xs text-gray-500">API Key</div>
                                <div class="mt-1 text-sm text-gray-800">{{ $project->openai_api_key ?? '—' }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Prompt ID</div>
                                <div class="mt-1 text-sm text-gray-800">{{ $project->openai_prompt_id ?? '—' }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Meta --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-3">Meta</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">
                            <div>
                                <div class="text-xs text-gray-500">{{ __('global.created_at') }}</div>
                                <div class="mt-1">{{ $project->created_at?->format('d/m/Y H:i') ?? '—' }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">{{ __('global.updated_at') }}</div>
                                <div class="mt-1">{{ $project->updated_at?->format('d/m/Y H:i') ?? '—' }}</div>
                            </div>
                        </div>
                    </div>

                </div> {{-- p-6 --}}
                
            </div> {{-- container --}}
        </div>
    </div>
</x-admin-layout>
