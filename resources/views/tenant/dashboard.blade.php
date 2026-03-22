<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-[#0f62fe] flex items-center justify-center shrink-0">
                    <span class="font-black text-white text-sm uppercase">{{ substr($tenant->name, 0, 2) }}</span>
                </div>
                <div>
                    <h2 class="font-bold text-gray-900 leading-tight">{{ $tenant->name }}</h2>
                    <p class="text-xs text-gray-500">{{ $tenant->slug }}.chunkiq.com</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if($role === 'admin')
                <a href="{{ route('tenant.members', ['tenantSlug' => $tenant->slug]) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-[#0f62fe] bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 hover:border-blue-300 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Members
                    <span class="bg-blue-200 text-blue-800 text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $memberCount }}</span>
                </a>
                @endif
                <span class="text-xs font-bold px-3 py-1.5 rounded-lg
                    {{ $role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ ucfirst($role) }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('info'))
                <div class="bg-blue-50 border border-blue-200 text-blue-700 rounded-lg px-4 py-3 text-sm">{{ session('info') }}</div>
            @endif

            {{-- ── Status ─────────────────────────────────────────────────────── --}}
            @php
                $connStatusBadge = $connectorCount > 0
                    ? ['label' => 'Active',   'class' => 'bg-green-100 text-green-700']
                    : ['label' => 'None yet', 'class' => 'bg-amber-100 text-amber-700'];

                if ($lastJob) {
                    $jobStatusMap = [
                        'completed' => ['label' => 'Last: done',   'class' => 'bg-green-100 text-green-700'],
                        'running'   => ['label' => 'Running',      'class' => 'bg-blue-100 text-blue-700'],
                        'failed'    => ['label' => 'Last: failed', 'class' => 'bg-red-100 text-red-600'],
                        'pending'   => ['label' => 'Queued',       'class' => 'bg-amber-100 text-amber-700'],
                    ];
                    $jobStatusBadge = $jobStatusMap[$lastJob->status] ?? ['label' => ucfirst($lastJob->status), 'class' => 'bg-gray-100 text-gray-600'];
                } else {
                    $jobStatusBadge = ['label' => 'No jobs yet', 'class' => 'bg-gray-100 text-gray-500'];
                }

                $cfgBadge = $configSteps === 3
                    ? ['label' => 'Complete',          'class' => 'bg-green-100 text-green-700']
                    : ($configSteps > 0
                        ? ['label' => "{$configSteps}/3 steps", 'class' => 'bg-amber-100 text-amber-700']
                        : ['label' => 'Not configured',         'class' => 'bg-red-100 text-red-600']);
            @endphp

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 pt-5 pb-3 border-b border-gray-100">
                    <h2 class="text-sm font-bold text-gray-500 uppercase tracking-widest">Status</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-gray-100">

                    {{-- Connectors --}}
                    <div class="flex items-center gap-4 px-6 py-5">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-[#0f62fe] shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-2xl font-extrabold text-gray-900 leading-none">{{ $connectorCount }}</p>
                            <p class="text-xs font-medium text-gray-500 mt-1">Connectors</p>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0 {{ $connStatusBadge['class'] }}">
                            {{ $connStatusBadge['label'] }}
                        </span>
                    </div>

                    {{-- Pipeline Jobs --}}
                    <div class="flex items-center gap-4 px-6 py-5">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-[#0f62fe] shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-2xl font-extrabold text-gray-900 leading-none">{{ $jobCount }}</p>
                            <p class="text-xs font-medium text-gray-500 mt-1">Pipeline Jobs</p>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0 {{ $jobStatusBadge['class'] }}">
                            {{ $jobStatusBadge['label'] }}
                        </span>
                    </div>

                    {{-- Members --}}
                    <div class="flex items-center gap-4 px-6 py-5">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-[#0f62fe] shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-2xl font-extrabold text-gray-900 leading-none">{{ $memberCount }}</p>
                            <p class="text-xs font-medium text-gray-500 mt-1">Members</p>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0 bg-blue-100 text-blue-700">
                            {{ $adminCount }} {{ $adminCount === 1 ? 'admin' : 'admins' }}
                        </span>
                    </div>

                    {{-- Azure Config --}}
                    <div class="flex items-center gap-4 px-6 py-5">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-[#0f62fe] shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-2xl font-extrabold leading-none {{ $configSteps === 3 ? 'text-green-600' : ($configSteps > 0 ? 'text-amber-500' : 'text-red-500') }}">
                                {{ $configSteps }}/3
                            </p>
                            <p class="text-xs font-medium text-gray-500 mt-1">Azure Config</p>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0 {{ $cfgBadge['class'] }}">
                            {{ $cfgBadge['label'] }}
                        </span>
                    </div>

                </div>
            </div>

            {{-- ── Admin Actions ───────────────────────────────────────────────── --}}
            @if($role === 'admin')
            @php
                $configBadgeText  = $configSteps === 3 ? 'Complete' : ($configSteps > 0 ? "{$configSteps}/3 steps" : 'Not configured');
                $configBadgeClass = $configSteps === 3 ? 'bg-green-100 text-green-700' : ($configSteps > 0 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-600');

                $connectorBadgeText  = $connectorCount > 0 ? "{$connectorCount} " . ($connectorCount === 1 ? 'connector' : 'connectors') : 'None yet';
                $connectorBadgeClass = $connectorCount > 0 ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700';

                if ($lastJob) {
                    $jobActionBadgeMap = [
                        'completed' => ['label' => 'Last: done',   'class' => 'bg-green-100 text-green-700'],
                        'running'   => ['label' => 'Running',      'class' => 'bg-blue-100 text-blue-700'],
                        'failed'    => ['label' => 'Last: failed', 'class' => 'bg-red-100 text-red-600'],
                        'pending'   => ['label' => 'Queued',       'class' => 'bg-amber-100 text-amber-700'],
                    ];
                    $jobActionBadge = $jobActionBadgeMap[$lastJob->status] ?? ['label' => ucfirst($lastJob->status), 'class' => 'bg-gray-100 text-gray-600'];
                } else {
                    $jobActionBadge = ['label' => 'No jobs yet', 'class' => 'bg-gray-100 text-gray-500'];
                }
            @endphp

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 pt-5 pb-3 border-b border-gray-100">
                    <h2 class="text-sm font-bold text-gray-500 uppercase tracking-widest">Admin Actions</h2>
                </div>
                <div class="divide-y divide-gray-100">

                    {{-- Azure Configuration --}}
                    <a href="{{ route('tenant.config.edit', ['tenantSlug' => $tenant->slug]) }}"
                       class="group flex items-center gap-4 px-6 py-4 hover:bg-blue-50/40 transition-colors">
                        <div class="w-9 h-9 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-[#0f62fe] group-hover:bg-[#0f62fe] group-hover:text-white group-hover:border-[#0f62fe] transition-colors shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 group-hover:text-[#0f62fe] transition-colors">Azure Configuration</p>
                            <p class="text-xs text-gray-400 mt-0.5">App registration, storage, AI Search credentials</p>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $configBadgeClass }}">{{ $configBadgeText }}</span>
                            <svg class="w-4 h-4 text-gray-300 group-hover:text-[#0f62fe] group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>

                    {{-- Connectors --}}
                    <a href="{{ route('tenant.connectors.index', ['tenantSlug' => $tenant->slug]) }}"
                       class="group flex items-center gap-4 px-6 py-4 hover:bg-blue-50/40 transition-colors">
                        <div class="w-9 h-9 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-[#0f62fe] group-hover:bg-[#0f62fe] group-hover:text-white group-hover:border-[#0f62fe] transition-colors shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 group-hover:text-[#0f62fe] transition-colors">Connectors</p>
                            <p class="text-xs text-gray-400 mt-0.5">SharePoint · Teams · OneDrive · OneNote</p>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $connectorBadgeClass }}">{{ $connectorBadgeText }}</span>
                            <svg class="w-4 h-4 text-gray-300 group-hover:text-[#0f62fe] group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>

                    {{-- Pipeline Jobs --}}
                    <a href="{{ route('tenant.jobs.index', ['tenantSlug' => $tenant->slug]) }}"
                       class="group flex items-center gap-4 px-6 py-4 hover:bg-blue-50/40 transition-colors">
                        <div class="w-9 h-9 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-[#0f62fe] group-hover:bg-[#0f62fe] group-hover:text-white group-hover:border-[#0f62fe] transition-colors shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 group-hover:text-[#0f62fe] transition-colors">Pipeline Jobs</p>
                            <p class="text-xs text-gray-400 mt-0.5">Ingestion, enrichment &amp; processing runs</p>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $jobActionBadge['class'] }}">{{ $jobActionBadge['label'] }}</span>
                            <svg class="w-4 h-4 text-gray-300 group-hover:text-[#0f62fe] group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>

                </div>
            </div>
            @endif

            <!-- Back to portal -->
            <div>
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back to all workspaces
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
