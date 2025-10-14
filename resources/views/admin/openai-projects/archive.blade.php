<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('admin/project.archive') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-2xl">
                    <p>{{ __('admin/project.confirm_archive') }}</p>

                    <!-- Change method to POST for correct route handling -->
                    <form method="POST" action="{{ route('admin.openai-projects.archive', $project['id']) }}" class="mt-6 space-y-6">
                        @csrf
                        @method('POST') <!-- Explicitly adding POST method here -->

                        <div class="flex items-center gap-4">
                            <a href="{{ route('admin.openai-projects.index') }}">
                                <x-secondary-button>{{ __('global.cancel') }}</x-secondary-button>
                            </a>
                            <x-danger-button>{{ __('global.archive') }}</x-danger-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
