<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-gray-900 leading-tight">Pipeline Jobs</h2>
                    <p class="text-xs text-gray-500">{{ $tenant->name }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('tenant.jobs.create', ['tenantSlug' => $tenant->slug]) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#0f62fe] text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    New Job
                </a>
                <a href="{{ route('tenant.dashboard', ['tenantSlug' => $tenant->slug]) }}"
                   class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>
            @endif

            @if($jobs->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-10 text-center">
                <p class="text-gray-400 text-sm">No jobs yet. Create one to start ingesting or processing data.</p>
            </div>
            @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 divide-y divide-gray-200">
                @foreach($jobs as $job)
                @php $color = $job->getStatusBadgeColor(); @endphp
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center gap-4 min-w-0">
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">{{ $job->name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ \App\Models\PipelineJob::TYPES[$job->type] }}
                                @if($job->connector) · {{ $job->connector->name }} @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-{{ $color }}-100 text-{{ $color }}-700">
                            {{ \App\Models\PipelineJob::STATUSES[$job->status]['label'] }}
                        </span>
                        <span class="text-xs text-gray-400">
                            {{ $job->updated_at->diffForHumans() }}
                        </span>
                        <a href="{{ route('tenant.jobs.show', ['tenantSlug' => $tenant->slug, 'job' => $job->id]) }}"
                           class="text-xs text-blue-600 hover:text-blue-800 font-semibold">View</a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
