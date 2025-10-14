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
                    <form method="POST" action="{{ route('admin.projects.store') }}" class="mt-6 space-y-6">
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

                        {{-- Script --}}
                        <!-- <div>
                            <x-input-label for="script" :value="__('admin/project.attributes.script')" />
                            <textarea 
                                id="script" 
                                name="script" 
                                rows="5" 
                                class="mt-1 block w-full border rounded p-2"
                            >{{-- old('script') --}}</textarea>
                            <x-input-error :messages="$errors->get('script')" class="mt-2" />
                        </div> -->

                        {{-- OpenAI API Key --}}
                        <div>
                            <x-input-label for="openai_api_key" :value="__('admin/project.attributes.openai_api_key')" />
                            <x-text-input 
                                id="openai_api_key" 
                                name="openai_api_key" 
                                type="text" 
                                class="mt-1 block w-full" 
                                value="{{ old('openai_api_key') }}"
                            />
                            <x-input-error :messages="$errors->get('openai_api_key')" class="mt-2" />
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

                        {{-- Image Generation (Select) --}}
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

                        {{-- Buttons --}}
                        <div class="flex items-center gap-4">
                            <a href="{{ route('admin.projects.index') }}">
                                <x-secondary-button>{{ __('global.back') }}</x-secondary-button>
                            </a>
                            <x-primary-button>{{ __('global.save') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
