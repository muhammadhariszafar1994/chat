<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('admin/project.create') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-2xl">
                    <!-- Tabs Navigation -->
                    <div class="flex border-b">
                        <button type="button" id="tab-project-details" class="py-2 px-4 text-blue-600 border-blue-600 focus:outline-none">Project Details</button>
                    </div>

                    <!-- Tab Content -->
                    <div id="content-project-details" class="tab-content mt-6">
                        <form method="POST" id="project-form" action="{{ route('admin.projects.store') }}" class="mt-6 space-y-6">
                            @csrf
                            {{-- Project Name --}}
                            <div>
                                <x-input-label for="name" :value="__('admin/project.attributes.name')" :required="true" />
                                <x-text-input 
                                    id="name" 
                                    name="name" 
                                    type="text" 
                                    class="mt-1 block w-full" 
                                    autocomplete="off" 
                                    value="{{ old('name') }}"
                                />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            {{-- Project ID (Dropdown) --}}
                            <div>
                                <x-input-label for="openai_projects" :value="__('admin/project.attributes.openai_projects')" />
                                <select id="openai_projects" name="openai_project_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">{{ __('global.select') }}</option>
                                    @foreach($projects as $key => $value)
                                        <option value="{{ $key }}" @selected(old('openai_project_id') == $key)>{{ $value }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('openai_project_id')" class="mt-2" />
                            </div>

                            {{-- OpenAI API Key (Dropdown) --}}
                            <div>
                                <x-input-label for="openai_api_key" :value="__('admin/project.attributes.openai_api_key')" />
                                <select id="openai_api_key" name="openai_api_key" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" disabled>
                                    <option value="">{{ __('global.select') }}</option>
                                    @foreach($apiKeys as $key => $value)
                                        <option value="{{ $key }}" @selected(old('openai_api_key') == $key)>{{ $value }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('openai_api_key')" class="mt-2" />
                            </div>


                            {{-- Client URL --}}
                            <div>
                                <x-input-label for="client_url" :value="__('admin/project.attributes.client_url')" />
                                <x-text-input 
                                    id="client_url" 
                                    name="client_url" 
                                    type="url" 
                                    class="mt-1 block w-full" 
                                    value="{{ old('client_url') }}"
                                    placeholder="https://example.com"
                                />
                                <x-input-error :messages="$errors->get('client_url')" class="mt-2" />
                            </div>

                            {{-- Theme --}}
                            <div>
                                <x-input-label for="theme_id" :value="__('admin/project.attributes.theme_id')" />
                                <x-select-input
                                    :options="$themes->pluck('name', 'id')"
                                    id="theme_id"
                                    name="theme_id"
                                    class="mt-1 block w-full"
                                    :selected="old('theme_id')"
                                />
                                <x-input-error :messages="$errors->get('theme_id')" class="mt-2" />
                            </div>

                            {{-- User --}}
                            <div>
                                <x-input-label for="user_id" :value="__('admin/project.attributes.user_id')" />
                                <x-select-input
                                    :options="$users->pluck('name', 'id')"
                                    id="user_id"
                                    name="user_id"
                                    class="mt-1 block w-full"
                                    :selected="old('user_id')"
                                />
                                <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                            </div>

                            {{-- OpenAI Prompt ID --}}
                            <div>
                                <x-input-label for="openai_prompt_id" :value="__('admin/project.attributes.openai_prompt_id')" />
                                <x-text-input 
                                    id="openai_prompt_id" 
                                    name="openai_prompt_id" 
                                    type="text" 
                                    class="mt-1 block w-full" 
                                    value="{{ old('openai_prompt_id') }}"
                                />
                                <x-input-error :messages="$errors->get('openai_prompt_id')" class="mt-2" />
                            </div>

                            {{-- Buttons --}}
                            <div class="flex items-center gap-4 mt-4">
                                <a href="{{ route('admin.projects.index') }}">
                                    <x-secondary-button>{{ __('global.back') }}</x-secondary-button>
                                </a>
                                <x-primary-button>{{ __('global.save') }}</x-primary-button>
                            </div>
                        </form>
                    </div>
{{-- 
                    <div id="content-chat-prompts" class="tab-content mt-6 space-y-6 hidden">                        
                        <div>
                            <x-input-label for="chat_prompt" :value="__('admin/project.attributes.chat_prompt')" />
                            <x-textarea 
                                id="chat_prompt" 
                                name="chat_prompt" 
                                class="mt-1 block w-full"
                            >{{ old('chat_prompt') }}</x-textarea>
                            <x-input-error :messages="$errors->get('chat_prompt')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="image_generation" :value="__('admin/project.attributes.image_generation')" />
                            <x-select-input
                                :options="[
                                    0 => __('global.no'),
                                    1 => __('global.yes'),
                                ]"
                                id="image_generation"
                                name="image_generation"
                                class="mt-1 block w-full"
                                :selected="old('image_generation')"
                            />
                            <x-input-error :messages="$errors->get('image_generation')" class="mt-2" />
                        </div>
--}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        

        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('tab-project-details').addEventListener('click', function() {
                document.getElementById('content-project-details').classList.remove('hidden');
                document.getElementById('content-chat-prompts').classList.add('hidden');
                document.getElementById('tab-project-details').classList.add('border-blue-600', 'text-blue-600');
                document.getElementById('tab-chat-prompts').classList.remove('border-blue-600', 'text-blue-600');
            });

            const projectSelect = document.getElementById('openai_projects');
            const apiKeySelect = document.getElementById('openai_api_key');

            if (!projectSelect || !apiKeySelect) {
                console.warn('Select inputs not found');
                return;
            }

            // Initially disable API key dropdown if no project is selected
            if (!projectSelect.value) {
                apiKeySelect.disabled = true;
            }

            projectSelect.addEventListener('change', function () {
                const projectId = this.value;

                // Reset the API key dropdown
                apiKeySelect.innerHTML = `<option value="">{{ __('global.select') }}</option>`;

                if (!projectId) {
                    apiKeySelect.disabled = true;
                    return;
                }

                fetch(`{{ route('admin.projectApiKeys') }}?project_id=${projectId}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    apiKeySelect.disabled = false;
                    apiKeySelect.innerHTML = '<option value="">{{ __('global.select') }}</option>';

                    if (Object.keys(data).length === 0) {
                        apiKeySelect.innerHTML = `<option value="">No API keys available</option>`;
                        apiKeySelect.disabled = true;
                        return;
                    }

                    for (const [label, key] of Object.entries(data)) {
                        const option = document.createElement('option');
                        option.value = key;
                        option.textContent = label;
                        apiKeySelect.appendChild(option);
                    }
                })
                .catch(error => {
                    console.error('API key fetch error:', error);
                    apiKeySelect.innerHTML = `<option value="">Error loading keys</option>`;
                    apiKeySelect.disabled = true;
                });
            });

            apiKeySelect.addEventListener('change', function () {
                const apiKey = this.value;
                const projectId = projectSelect.value;

                if (apiKey && projectId) {
                    fetch(`{{ route('admin.projectApiKeyDetails') }}?project_id=${projectId}&key_id=${apiKey}`, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Process the API key details (can be displayed or used as needed)
                        console.log('API Key Details:', data);
                    })
                    .catch(error => {
                        console.error('Error fetching API key details:', error);
                    });
                }
            });
        });
    </script>
</x-admin-layout>
