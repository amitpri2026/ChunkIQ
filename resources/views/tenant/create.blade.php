<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Workspace</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">

                <h3 class="font-bold text-gray-800 text-lg mb-1">New Workspace</h3>
                <p class="text-sm text-gray-400 mb-6">Your workspace will be accessible at <span class="font-mono text-blue-600">yourslug.chunkiq.com</span></p>

                <form method="POST" action="{{ route('tenants.store') }}" class="space-y-5">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" value="Workspace Name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                            placeholder="Acme Corporation"
                            value="{{ old('name') }}" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <!-- Slug -->
                    <div>
                        <x-input-label for="slug" value="Subdomain Slug" />
                        <div class="mt-1 flex rounded-lg shadow-sm">
                            <x-text-input id="slug" name="slug" type="text"
                                class="block w-full rounded-r-none border-r-0 lowercase"
                                placeholder="acme"
                                value="{{ old('slug') }}" required
                                pattern="[a-z0-9\-]+"
                                title="Only lowercase letters, numbers and hyphens" />
                            <span class="inline-flex items-center px-3 rounded-r-lg border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm font-mono">
                                .chunkiq.com
                            </span>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Lowercase letters, numbers and hyphens only. Cannot be changed later.</p>
                        <x-input-error :messages="$errors->get('slug')" class="mt-1" />
                    </div>

                    <!-- Description -->
                    <div>
                        <x-input-label for="description" value="Description (optional)" />
                        <textarea id="description" name="description" rows="2"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm"
                            placeholder="Short description of your workspace">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-1" />
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                        <x-primary-button>Create Workspace</x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        // Auto-generate slug from name
        document.getElementById('name').addEventListener('input', function () {
            const slugField = document.getElementById('slug');
            if (!slugField.dataset.touched) {
                slugField.value = this.value
                    .toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .trim()
                    .replace(/[\s_]+/g, '-')
                    .replace(/-+/g, '-');
            }
        });
        document.getElementById('slug').addEventListener('input', function () {
            this.dataset.touched = '1';
        });
    </script>
</x-app-layout>
