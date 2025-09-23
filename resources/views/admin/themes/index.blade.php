<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('admin/theme.title_plural') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-table
                :create="auth()->user()->can('theme_create') ? ['url' => route('admin.themes.create'), 'label' => __('admin/theme.create')] : null"
                :headers="['id', 'name', 'chat_header_bg_color', 'chat_header_font_family', 'is_active', 'created_at']"
                :labels="[
                    'ID',
                    __('admin/theme.attributes.name'),
                    __('admin/theme.attributes.chat_header_bg_color'),
                    __('admin/theme.attributes.chat_header_font_family'),
                    __('admin/theme.attributes.is_active'),
                    __('global.created_at'),
                    __('global.actions')
                ]"
                :data="$themes->map(function($theme) {
                    return [
                        'id' => $theme->id,
                        'name' => e($theme->name),
                        'chat_header_bg_color' => $theme->chat_header_bg_color
                            ? '<span class=\'inline-block w-6 h-6 rounded-full border mr-2\' style=\'background-color:' . $theme->chat_header_bg_color . '\'></span>' . e($theme->chat_header_bg_color)
                            : '-',
                        'chat_header_font_family' => e($theme->chat_header_font_family ?? '-'),
                        'is_active' => $theme->is_active
                            ? '<span class=\'bg-green-100 text-green-700 text-sm px-2 py-1 rounded-md\'>' . __('global.active') . '</span>'
                            : '<span class=\'bg-red-100 text-red-700 text-sm px-2 py-1 rounded-md\'>' . __('global.inactive') . '</span>',
                        'created_at' => $theme->created_at->format('d/m/Y H:i'),
                        'actions' => collect([
                            auth()->user()->can('theme_view') ? [
                                'name' => 'show',
                                'url' => route('admin.themes.show', $theme->id),
                                'label' => __('global.details'),
                                'color' => 'green'
                            ] : null,
                            auth()->user()->can('theme_edit') ? [
                                'name' => 'edit',
                                'url' => route('admin.themes.edit', $theme->id),
                                'label' => __('global.edit'),
                                'color' => 'blue'
                            ] : null,
                            auth()->user()->can('theme_delete') ? [
                                'name' => 'delete',
                                'url' => route('admin.themes.destroy', $theme->id),
                                'label' => __('global.delete'),
                            ] : null,
                        ])->filter()->toArray(),
                    ];
                })"
                :pagination="$themes->links()"
            />
        </div>
    </div>
</x-admin-layout>
