<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('admin/visitor.title_plural') }}
        </h2>
    </x-slot>

    <style>
        /* Optionally add global CSS here if you prefer */
        table {
            table-layout: fixed;
            width: 100%;
        }
        table td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        table td:last-child {
            white-space: normal;
            overflow: visible;
            text-overflow: clip;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- :create="auth()->user()->can('visitor_create') ? ['url' => route('admin.visitors.create'), 'label' => __('admin/visitor.create')] : null" -->
            <x-table
                
                :headers="['id', 'ip_address', 'conversation_id', 'created_at']"
                :labels="[
                    'ID',
                    __('admin/visitor.attributes.ip_address'),
                    __('admin/visitor.attributes.conversation_id'),
                    __('global.created_at'),
                    __('global.actions')
                ]"
                :data="$visitors->map(function($visitor) {
                    return [
                        'id' => $visitor->id,
                        'ip_address' => e($visitor->ip_address) ?? '-',
                        'conversation_id' => e($visitor->conversation_id) ?? '-',
                        'created_at' => $visitor->created_at->format('d/m/Y H:i'),
                        'actions' => collect([
                            auth()->user()->can('visitor_view') ? [
                                'name' => 'show',
                                'url' => route('admin.visitors.show', $visitor->id),
                                'label' => __('global.details'),
                                'color' => 'green'
                            ] : null,
                            auth()->user()->can('visitor_edit') ? [
                                'name' => 'edit',
                                'url' => route('admin.visitors.edit', $visitor->id),
                                'label' => __('global.edit'),
                                'color' => 'blue'
                            ] : null,
                            auth()->user()->can('visitor_delete') ? [
                                'name' => 'delete',
                                'url' => route('admin.visitors.destroy', $visitor->id),
                                'label' => __('global.delete'),
                            ] : null,
                        ])->filter()->toArray(),
                    ];
                })"
                :pagination="$visitors->links()"
            />
        </div>
    </div>
</x-admin-layout>
