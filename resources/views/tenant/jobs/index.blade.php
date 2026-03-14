<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $tenant->name }} — Pipeline Jobs</h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('tenant.jobs.create', ['tenantSlug' => $tenant->slug]) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    + New Job
                </a>
                <a href="{{ route('tenant.dashboard', ['tenantSlug' => $tenant->slug]) }}" class="text-sm text-gray-400 hover:text-gray-600">← Dashboard</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>
            @endif

            @if($jobs->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-10 text-center">
                <p class="text-gray-400 text-sm">No jobs yet. Create one to start ingesting or processing data.</p>
            </div>
            @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 divide-y divide-gray-100">
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
