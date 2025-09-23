<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('admin/theme.show') ?? 'Theme Details' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">

                {{-- Header with back button --}}
                <div class="p-4 flex items-center justify-between border-b">
                    <div class="text-lg font-medium text-gray-700">
                        {{ $theme->name }}
                    </div>
                    <div>
                        <a href="{{ route('admin.themes.index') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-sm rounded-md hover:bg-gray-200">
                            {{ __('global.back') }}
                        </a>
                    </div>
                </div>

                <div class="p-6 space-y-6">

                    {{-- Preview --}}
                    <div class="rounded-md border p-4 bg-gray-50">
                        <div class="text-sm text-gray-600 mb-2">Preview</div>
                        <div class="rounded-md overflow-hidden">
                            <div style="background-color: {{ $theme->chat_header_bg_color ?? '#1f2937' }}; color: {{ $theme->chat_header_text_color ?? '#fff' }};" class="px-4 py-3">
                                <div class="text-lg font-semibold">{{ $theme->name }}</div>
                            </div>
                            <div class="px-4 py-6" style="font-family: {{ $theme->chat_message_font_family ?? 'inherit' }};">
                                <p class="text-sm">
                                    Chat body preview with user/bot colors applied.
                                </p>

                                {{-- Button image --}}
                                <div class="mt-3">
                                    <div class="text-sm text-gray-500">Button Image</div>
                                    @if($theme->getFirstMediaUrl('chat_button_image'))
                                        <img src="{{ $theme->getFirstMediaUrl('chat_button_image') }}" 
                                            alt="Button Image" 
                                            class="h-16 rounded border mt-1">
                                    @else
                                        <div class="text-sm text-gray-400 mt-1">No button image uploaded</div>
                                    @endif
                                </div>

                                {{-- Toggle image --}}
                                <div class="mt-3">
                                    <div class="text-sm text-gray-500">Toggle Image</div>
                                    @if($theme->getFirstMediaUrl('chat_toggle_image'))
                                        <img src="{{ $theme->getFirstMediaUrl('chat_toggle_image') }}" 
                                            alt="Toggle Image" 
                                            class="h-16 rounded border mt-1">
                                    @else
                                        <div class="text-sm text-gray-400 mt-1">No toggle image uploaded</div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- General --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-3">General</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <div class="text-sm text-gray-500">Name</div>
                                <div class="mt-1 text-sm text-gray-800">{{ $theme->name ?? '—' }}</div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-500">{{ __('admin/theme.attributes.is_active') }}</div>
                                <div class="mt-1">
                                    @if($theme->is_active)
                                        <span class="inline-flex items-center px-2 py-1 rounded-md bg-green-100 text-green-700 text-xs font-medium">Active</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-md bg-red-100 text-red-700 text-xs font-medium">Inactive</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Background Colors --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-3">Background Colors</h3>
                        @php
                            $bgColors = [
                                'chat_header_bg_color' => 'Header',
                                'chat_body_bg_color' => 'Body',
                                'chat_bot_bg_color' => 'Bot',
                                'chat_user_bg_color' => 'User',
                                'chat_button_bg_color' => 'Button',
                                'chat_toggle_bg_color' => 'Toggle',
                            ];
                        @endphp
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            @foreach($bgColors as $field => $label)
                                <div class="p-3 border rounded bg-white">
                                    <div class="text-xs text-gray-500 mb-2">{{ $label }}</div>
                                    @if(!empty($theme->$field))
                                        <div class="flex items-center gap-3">
                                            <span class="w-8 h-8 rounded border" style="background-color: {{ $theme->$field }}"></span>
                                            <div class="text-sm text-gray-800">{{ $theme->$field }}</div>
                                        </div>
                                    @else
                                        <div class="text-sm text-gray-400">—</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Text Colors --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-3">Text Colors</h3>
                        @php
                            $textColors = [
                                'chat_header_text_color' => 'Header',
                                'chat_bot_text_color' => 'Bot',
                                'chat_user_text_color' => 'User',
                            ];
                        @endphp
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            @foreach($textColors as $field => $label)
                                <div class="p-3 border rounded bg-white">
                                    <div class="text-xs text-gray-500 mb-2">{{ $label }}</div>
                                    @if(!empty($theme->$field))
                                        <div class="flex items-center gap-3">
                                            <span class="w-8 h-8 rounded border" style="background-color: {{ $theme->$field }}"></span>
                                            <div class="text-sm text-gray-800">{{ $theme->$field }}</div>
                                        </div>
                                    @else
                                        <div class="text-sm text-gray-400">—</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Fonts --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-3">Fonts</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <div class="text-xs text-gray-500">Header Font Family</div>
                                <div class="mt-1 text-sm text-gray-800">{{ $theme->chat_header_font_family ?? '—' }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Header Font Size</div>
                                <div class="mt-1 text-sm text-gray-800">{{ $theme->chat_header_font_size ?? '—' }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Message Font Family</div>
                                <div class="mt-1 text-sm text-gray-800">{{ $theme->chat_message_font_family ?? '—' }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Message Font Size</div>
                                <div class="mt-1 text-sm text-gray-800">{{ $theme->chat_message_font_size ?? '—' }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Meta --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-3">Meta</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">
                            <div>
                                <div class="text-xs text-gray-500">{{ __('global.created_at') }}</div>
                                <div class="mt-1">{{ $theme->created_at?->format('d/m/Y H:i') ?? '—' }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">{{ __('global.updated_at') }}</div>
                                <div class="mt-1">{{ $theme->updated_at?->format('d/m/Y H:i') ?? '—' }}</div>
                            </div>
                        </div>
                    </div>

                </div> {{-- p-6 --}}
            </div> {{-- container --}}
        </div>
    </div>
</x-admin-layout>
