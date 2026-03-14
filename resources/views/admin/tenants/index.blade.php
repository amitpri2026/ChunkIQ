<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold px-2.5 py-1 bg-red-100 text-red-700 rounded-full uppercase tracking-wide">Super Admin</span>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">All Workspaces</h2>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-400 hover:text-gray-600">← Overview</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 divide-y divide-gray-100">
                @foreach($tenants as $tenant)
                <div class="flex items-center justify-between p-4">
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">{{ $tenant->name }}</p>
                        <p class="text-xs text-gray-400">
                            {{ $tenant->slug }}.chunkiq.com
                            · Owner: {{ $tenant->owner->name }}
                            · {{ $tenant->users_count }} member{{ $tenant->users_count !== 1 ? 's' : '' }}
                            · {{ $tenant->connectors_count }} connector{{ $tenant->connectors_count !== 1 ? 's' : '' }}
                            · {{ $tenant->pipeline_jobs_count }} job{{ $tenant->pipeline_jobs_count !== 1 ? 's' : '' }}
                        </p>
                    </div>
                    <a href="{{ route('admin.tenants.show', $tenant->id) }}"
                       class="text-xs text-blue-600 hover:text-blue-800 font-semibold">View →</a>
                </div>
                @endforeach
            </div>

            <div class="mt-4">{{ $tenants->links() }}</div>
        </div>
    </div>
</x-app-layout>
