<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="text-xs font-bold px-2.5 py-1 bg-red-100 text-red-700 rounded-full uppercase tracking-wide">Super Admin</span>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Platform Overview</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>
            @endif

            <!-- Platform stats -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                @php
                $platformStats = [
                    ['label'=>'Workspaces', 'value'=>$stats['tenants'], 'icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'border'=>'border-blue-100',   'bg'=>'from-blue-50',   'icon_bg'=>'bg-blue-100',   'icon_color'=>'text-blue-600',   'num_color'=>'text-blue-700'],
                    ['label'=>'Users',      'value'=>$stats['users'],   'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'border'=>'border-purple-100', 'bg'=>'from-purple-50', 'icon_bg'=>'bg-purple-100', 'icon_color'=>'text-purple-600', 'num_color'=>'text-purple-700'],
                    ['label'=>'Total Jobs', 'value'=>$stats['jobs'],    'icon'=>'M13 10V3L4 14h7v7l9-11h-7z', 'border'=>'border-gray-200',   'bg'=>'from-gray-50',   'icon_bg'=>'bg-gray-100',   'icon_color'=>'text-gray-600',   'num_color'=>'text-gray-700'],
                    ['label'=>'Running',    'value'=>$stats['running'], 'icon'=>'M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'border'=>'border-green-100',  'bg'=>'from-green-50',  'icon_bg'=>'bg-green-100',  'icon_color'=>'text-green-600',  'num_color'=>'text-green-700'],
                    ['label'=>'Failed',     'value'=>$stats['failed'],  'icon'=>'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z', 'border'=>'border-red-100',    'bg'=>'from-red-50',    'icon_bg'=>'bg-red-100',    'icon_color'=>'text-red-500',    'num_color'=>'text-red-600'],
                ];
                @endphp
                @foreach($platformStats as $stat)
                <div class="flex flex-col p-5 rounded-xl border-2 {{ $stat['border'] }} bg-gradient-to-br {{ $stat['bg'] }} to-white shadow-sm">
                    <div class="w-9 h-9 rounded-lg {{ $stat['icon_bg'] }} flex items-center justify-center {{ $stat['icon_color'] }} mb-3">
                        <svg class="w-4.5 h-4.5 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/>
                        </svg>
                    </div>
                    <p class="text-2xl font-extrabold {{ $stat['num_color'] }}">{{ $stat['value'] }}</p>
                    <p class="text-xs font-semibold text-gray-500 mt-1">{{ $stat['label'] }}</p>
                </div>
                @endforeach
            </div>

            <!-- Nav -->
            <div class="flex gap-3">
                <a href="{{ route('admin.tenants') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-lg hover:border-blue-400 hover:text-blue-600 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    All Workspaces
                </a>
                <a href="{{ route('admin.users') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-lg hover:border-blue-400 hover:text-blue-600 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    All Users
                </a>
                <a href="{{ route('admin.system-config') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-lg hover:border-blue-400 hover:text-blue-600 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    System Config
                </a>
            </div>

            <!-- Recent Jobs -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="font-bold text-gray-700 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Recent Jobs
                </h2>
                @if($recentJobs->isEmpty())
                    <p class="text-sm text-gray-400">No jobs yet.</p>
                @else
                <div class="divide-y divide-gray-200">
                    @foreach($recentJobs as $job)
                    @php $color = $job->getStatusBadgeColor(); @endphp
                    <div class="flex items-center justify-between py-3">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $job->name }}</p>
                            <p class="text-xs text-gray-400">
                                {{ $job->tenant->name }}
                                @if($job->connector) · {{ $job->connector->name }} @endif
                                · {{ $job->updated_at->diffForHumans() }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-semibold px-2 py-1 rounded-full bg-{{ $color }}-100 text-{{ $color }}-700">
                                {{ \App\Models\PipelineJob::STATUSES[$job->status]['label'] }}
                            </span>
                            @if(in_array($job->status, ['pending', 'running']))
                            <form method="POST" action="{{ route('admin.jobs.cancel', $job->id) }}">
                                @csrf
                                <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-semibold">Cancel</button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Recent Tenants -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="font-bold text-gray-700 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Recently Created Workspaces
                </h2>
                <div class="divide-y divide-gray-200">
                    @foreach($recentTenants as $tenant)
                    <div class="flex items-center justify-between py-3">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $tenant->name }}</p>
                            <p class="text-xs text-gray-400">{{ $tenant->slug }}.chunkiq.com · Owner: {{ $tenant->owner->name }}</p>
                        </div>
                        <a href="{{ route('admin.tenants.show', $tenant->id) }}"
                           class="text-xs text-blue-600 hover:text-blue-800 font-semibold">View</a>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
