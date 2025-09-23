<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('admin/theme.edit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-2xl">
                    <form method="POST" action="{{ route('admin.themes.update', $theme->id) }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Theme Name --}}
                        <div>
                            <x-input-label for="name" :value="__('admin/theme.attributes.name')" :required="true" />
                            <x-text-input 
                                id="name" 
                                name="name" 
                                type="text"
                                value="{{ old('name', $theme->name) }}"
                                class="mt-1 block w-full" 
                                autocomplete="off" 
                            />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{-- Chat Colors --}}
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @foreach([
                                'chat_header_bg_color', 'chat_body_bg_color', 
                                'chat_bot_bg_color', 'chat_user_bg_color'
                            ] as $colorField)
                                <div>
                                    <x-input-label for="{{ $colorField }}" :value="__('admin/theme.attributes.' . $colorField)" />
                                    <input 
                                        id="{{ $colorField }}" 
                                        name="{{ $colorField }}" 
                                        type="color" 
                                        value="{{ old($colorField, $theme->$colorField ?? '#000000') }}"
                                        class="mt-1 block w-20 h-10 p-1 rounded border"
                                    />
                                    <x-input-error :messages="$errors->get($colorField)" class="mt-2" />
                                </div>
                            @endforeach
                        </div>

                        {{-- Text Colors --}}
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @foreach([
                                'chat_header_text_color', 'chat_bot_text_color', 'chat_user_text_color'
                            ] as $colorField)
                                <div>
                                    <x-input-label for="{{ $colorField }}" :value="__('admin/theme.attributes.' . $colorField)" />
                                    <input 
                                        id="{{ $colorField }}" 
                                        name="{{ $colorField }}" 
                                        type="color" 
                                        value="{{ old($colorField, $theme->$colorField ?? '#000000') }}"
                                        class="mt-1 block w-20 h-10 p-1 rounded border"
                                    />
                                    <x-input-error :messages="$errors->get($colorField)" class="mt-2" />
                                </div>
                            @endforeach
                        </div>

                        {{-- Buttons --}}
                        <div>
                            <x-input-label for="chat_button_bg_color" :value="__('admin/theme.attributes.chat_button_bg_color')" />
                            <input 
                                id="chat_button_bg_color" 
                                name="chat_button_bg_color" 
                                type="color" 
                                value="{{ old('chat_button_bg_color', $theme->chat_button_bg_color ?? '#000000') }}"
                                class="mt-1 block w-20 h-10 p-1 rounded border"
                            />
                            <x-input-error :messages="$errors->get('chat_button_bg_color')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="chat_button_image" :value="__('admin/theme.attributes.chat_button_image')" />
                            <input 
                                id="chat_button_image" 
                                name="chat_button_image" 
                                type="file" 
                                class="mt-1 block w-full"
                                accept="image/*"
                            />
                            @if($theme->getFirstMediaUrl('chat_button_image'))
                                <div class="mt-2">
                                    <img src="{{ $theme->getFirstMediaUrl('chat_button_image') }}" 
                                         alt="Chat Button Image" 
                                         class="h-16 rounded border">
                                </div>
                            @endif
                            <x-input-error :messages="$errors->get('chat_button_image')" class="mt-2" />
                        </div>

                        {{-- Toggle --}}
                        <div>
                            <x-input-label for="chat_toggle_bg_color" :value="__('admin/theme.attributes.chat_toggle_bg_color')" />
                            <input 
                                id="chat_toggle_bg_color" 
                                name="chat_toggle_bg_color" 
                                type="color" 
                                value="{{ old('chat_toggle_bg_color', $theme->chat_toggle_bg_color ?? '#000000') }}"
                                class="mt-1 block w-20 h-10 p-1 rounded border"
                            />
                            <x-input-error :messages="$errors->get('chat_toggle_bg_color')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="chat_toggle_image" :value="__('admin/theme.attributes.chat_toggle_image')" />
                            <input 
                                id="chat_toggle_image" 
                                name="chat_toggle_image" 
                                type="file" 
                                class="mt-1 block w-full"
                                accept="image/*"
                            />
                            @if($theme->getFirstMediaUrl('chat_toggle_image'))
                                <div class="mt-2">
                                    <img src="{{ $theme->getFirstMediaUrl('chat_toggle_image') }}" 
                                         alt="Chat Toggle Image" 
                                         class="h-16 rounded border">
                                </div>
                            @endif
                            <x-input-error :messages="$errors->get('chat_toggle_image')" class="mt-2" />
                        </div>

                        {{-- Fonts --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="chat_header_font_family" :value="__('admin/theme.attributes.chat_header_font_family')" />
                                <x-text-input 
                                    id="chat_header_font_family" 
                                    name="chat_header_font_family" 
                                    type="text"
                                    value="{{ old('chat_header_font_family', $theme->chat_header_font_family) }}"
                                    class="mt-1 block w-full" 
                                />
                                <x-input-error :messages="$errors->get('chat_header_font_family')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="chat_header_font_size" :value="__('admin/theme.attributes.chat_header_font_size')" />
                                <x-text-input 
                                    id="chat_header_font_size" 
                                    name="chat_header_font_size" 
                                    type="text"
                                    value="{{ old('chat_header_font_size', $theme->chat_header_font_size) }}"
                                    placeholder="e.g. 16px"
                                    class="mt-1 block w-full" 
                                />
                                <x-input-error :messages="$errors->get('chat_header_font_size')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="chat_message_font_family" :value="__('admin/theme.attributes.chat_message_font_family')" />
                                <x-text-input 
                                    id="chat_message_font_family" 
                                    name="chat_message_font_family" 
                                    type="text"
                                    value="{{ old('chat_message_font_family', $theme->chat_message_font_family) }}"
                                    class="mt-1 block w-full" 
                                />
                                <x-input-error :messages="$errors->get('chat_message_font_family')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="chat_message_font_size" :value="__('admin/theme.attributes.chat_message_font_size')" />
                                <x-text-input 
                                    id="chat_message_font_size" 
                                    name="chat_message_font_size" 
                                    type="text"
                                    value="{{ old('chat_message_font_size', $theme->chat_message_font_size) }}"
                                    placeholder="e.g. 14px"
                                    class="mt-1 block w-full" 
                                />
                                <x-input-error :messages="$errors->get('chat_message_font_size')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Active Theme --}}
                        <div>
                            <x-input-label for="is_active" :value="__('admin/theme.attributes.is_active')" />
                            <x-select-input
                                :options="[
                                    0 => __('global.no'),
                                    1 => __('global.yes'),
                                ]"
                                :selected="old('is_active', $theme->is_active)"
                                id="is_active"
                                name="is_active"
                                class="mt-1 block w-full"
                            />
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>

                        {{-- Buttons --}}
                        <div class="flex items-center gap-4">
                            <a href="{{ route('admin.themes.index') }}">
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
