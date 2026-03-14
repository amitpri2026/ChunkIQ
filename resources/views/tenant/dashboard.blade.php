<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $tenant->name }}</h2>
                <p class="text-xs text-gray-400 mt-0.5">{{ $tenant->slug }}.chunkiq.com</p>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                {{ $role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600' }}">
                {{ ucfirst($role) }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>
            @endif
            @if(session('info'))
                <div class="bg-blue-50 border border-blue-200 text-blue-700 rounded-lg px-4 py-3 text-sm">{{ session('info') }}</div>
            @endif

            <!-- Summary cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Connectors</p>
                    <p class="text-3xl font-extrabold text-gray-800">—</p>
                    <p class="text-xs text-gray-400 mt-1">SharePoint · Teams · OneDrive</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Jobs</p>
                    <p class="text-3xl font-extrabold text-gray-800">—</p>
                    <p class="text-xs text-gray-400 mt-1">Ingestion &amp; processing</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Members</p>
                    <p class="text-3xl font-extrabold text-gray-800">—</p>
                    <p class="text-xs text-gray-400 mt-1">Admins &amp; users</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Azure Config</p>
                    <p class="text-3xl font-extrabold
                        {{ $tenant->getConfig('adls_account_name') ? 'text-green-600' : 'text-red-500' }}">
                        {{ $tenant->getConfig('adls_account_name') ? 'Set' : 'Pending' }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">ADLS &amp; credentials</p>
                </div>
            </div>

            <!-- Quick actions (admin only) -->
            @if($role === 'admin')
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="font-bold text-gray-700 mb-4">Admin Actions</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <a href="{{ route('tenant.config.edit', ['tenantSlug' => $tenant->slug]) }}"
                       class="flex items-center gap-3 p-4 rounded-lg border border-gray-100 hover:border-blue-200 hover:bg-blue-50 transition-colors">
                        <span class="text-2xl">⚙️</span>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Azure Configuration</p>
                            <p class="text-xs text-gray-400">ADLS, secrets, keys</p>
                        </div>
                    </a>
                    <a href="{{ route('tenant.members', ['tenantSlug' => $tenant->slug]) }}"
                       class="flex items-center gap-3 p-4 rounded-lg border border-gray-100 hover:border-blue-200 hover:bg-blue-50 transition-colors">
                        <span class="text-2xl">👥</span>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Manage Members</p>
                            <p class="text-xs text-gray-400">Roles &amp; invite links</p>
                        </div>
                    </a>
                    <a href="{{ route('tenant.connectors.index', ['tenantSlug' => $tenant->slug]) }}"
                       class="flex items-center gap-3 p-4 rounded-lg border border-gray-100 hover:border-blue-200 hover:bg-blue-50 transition-colors">
                        <span class="text-2xl">🔌</span>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Connectors</p>
                            <p class="text-xs text-gray-400">SharePoint · Teams · OneDrive · OneNote</p>
                        </div>
                    </a>
                    <a href="{{ route('tenant.jobs.index', ['tenantSlug' => $tenant->slug]) }}"
                       class="flex items-center gap-3 p-4 rounded-lg border border-gray-100 hover:border-blue-200 hover:bg-blue-50 transition-colors">
                        <span class="text-2xl">⚡</span>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Pipeline Jobs</p>
                            <p class="text-xs text-gray-400">Ingestion &amp; processing</p>
                        </div>
                    </a>
                </div>
            </div>
            @endif

            <!-- Back to portal -->
            <div>
                <a href="{{ route('dashboard') }}"
                   class="text-sm text-gray-400 hover:text-gray-600 transition-colors">
                    ← Back to all workspaces
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
