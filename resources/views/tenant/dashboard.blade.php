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
            @php
                // Azure Config badge
                $configBadgeText  = $configSteps === 3 ? 'All done' : ($configSteps > 0 ? "{$configSteps}/3 done" : 'Pending');
                $configBadgeClass = $configSteps === 3
                    ? 'bg-green-100 text-green-700'
                    : ($configSteps > 0 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-600');

                // Members badge
                $memberBadgeText  = $memberCount === 1 ? '1 member' : "{$memberCount} members";

                // Connectors badge
                $connectorBadgeText  = $connectorCount > 0 ? ($connectorCount === 1 ? '1 connector' : "{$connectorCount} connectors") : 'None yet';
                $connectorBadgeClass = $connectorCount > 0 ? 'bg-teal-100 text-teal-700' : 'bg-amber-100 text-amber-700';

                // Jobs badge
                if ($lastJob) {
                    $jobBadgeMap = [
                        'completed' => ['label' => 'Last: completed', 'class' => 'bg-green-100 text-green-700'],
                        'running'   => ['label' => 'Running…',        'class' => 'bg-blue-100 text-blue-700'],
                        'failed'    => ['label' => 'Last: failed',    'class' => 'bg-red-100 text-red-600'],
                        'pending'   => ['label' => 'Queued',          'class' => 'bg-amber-100 text-amber-700'],
                    ];
                    $jobBadge = $jobBadgeMap[$lastJob->status] ?? ['label' => ucfirst($lastJob->status), 'class' => 'bg-gray-100 text-gray-600'];
                } else {
                    $jobBadge = ['label' => 'No jobs yet', 'class' => 'bg-gray-100 text-gray-500'];
                }
            @endphp

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="font-bold text-gray-700 mb-5">Admin Actions</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                    {{-- Azure Configuration --}}
                    <a href="{{ route('tenant.config.edit', ['tenantSlug' => $tenant->slug]) }}"
                       class="group relative flex flex-col p-5 rounded-xl border-2 border-blue-100 bg-gradient-to-br from-blue-50 to-white hover:border-blue-300 hover:shadow-md transition-all duration-200">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 group-hover:bg-blue-200 transition-colors flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $configBadgeClass }}">
                                {{ $configBadgeText }}
                            </span>
                        </div>
                        <p class="font-bold text-gray-800 text-sm leading-tight">Azure Configuration</p>
                        <p class="text-xs text-gray-500 mt-1">Credentials, keys &amp; search</p>
                        <div class="mt-4 flex items-center gap-1 text-xs font-semibold text-blue-600 group-hover:text-blue-700">
                            Configure
                            <svg class="w-3 h-3 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>

                    {{-- Manage Members --}}
                    <a href="{{ route('tenant.members', ['tenantSlug' => $tenant->slug]) }}"
                       class="group relative flex flex-col p-5 rounded-xl border-2 border-purple-100 bg-gradient-to-br from-purple-50 to-white hover:border-purple-300 hover:shadow-md transition-all duration-200">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600 group-hover:bg-purple-200 transition-colors flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-purple-100 text-purple-700">
                                {{ $memberBadgeText }}
                            </span>
                        </div>
                        <p class="font-bold text-gray-800 text-sm leading-tight">Manage Members</p>
                        <p class="text-xs text-gray-500 mt-1">Roles &amp; invite links</p>
                        <div class="mt-4 flex items-center gap-1 text-xs font-semibold text-purple-600 group-hover:text-purple-700">
                            Manage
                            <svg class="w-3 h-3 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>

                    {{-- Connectors --}}
                    <a href="{{ route('tenant.connectors.index', ['tenantSlug' => $tenant->slug]) }}"
                       class="group relative flex flex-col p-5 rounded-xl border-2 border-teal-100 bg-gradient-to-br from-teal-50 to-white hover:border-teal-300 hover:shadow-md transition-all duration-200">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-10 h-10 rounded-lg bg-teal-100 flex items-center justify-center text-teal-600 group-hover:bg-teal-200 transition-colors flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $connectorBadgeClass }}">
                                {{ $connectorBadgeText }}
                            </span>
                        </div>
                        <p class="font-bold text-gray-800 text-sm leading-tight">Connectors</p>
                        <p class="text-xs text-gray-500 mt-1">SharePoint · Teams · OneDrive</p>
                        <div class="mt-4 flex items-center gap-1 text-xs font-semibold text-teal-600 group-hover:text-teal-700">
                            View all
                            <svg class="w-3 h-3 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>

                    {{-- Pipeline Jobs --}}
                    <a href="{{ route('tenant.jobs.index', ['tenantSlug' => $tenant->slug]) }}"
                       class="group relative flex flex-col p-5 rounded-xl border-2 border-orange-100 bg-gradient-to-br from-orange-50 to-white hover:border-orange-300 hover:shadow-md transition-all duration-200">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center text-orange-600 group-hover:bg-orange-200 transition-colors flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $jobBadge['class'] }}">
                                {{ $jobBadge['label'] }}
                            </span>
                        </div>
                        <p class="font-bold text-gray-800 text-sm leading-tight">Pipeline Jobs</p>
                        <p class="text-xs text-gray-500 mt-1">Ingestion &amp; processing</p>
                        <div class="mt-4 flex items-center gap-1 text-xs font-semibold text-orange-600 group-hover:text-orange-700">
                            View jobs
                            <svg class="w-3 h-3 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                            </svg>
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
