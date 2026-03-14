<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $job->name }}</h2>
                <p class="text-xs text-gray-400 mt-0.5">{{ \App\Models\PipelineJob::TYPES[$job->type] }}</p>
            </div>
            <a href="{{ route('tenant.jobs.index', ['tenantSlug' => $tenant->slug]) }}" class="text-sm text-gray-400 hover:text-gray-600">← Jobs</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">{{ $errors->first() }}</div>
            @endif

            <!-- Status card -->
            @php $color = $job->getStatusBadgeColor(); @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-bold text-gray-500 uppercase tracking-wide">Status</span>
                    <span class="text-sm font-bold px-3 py-1.5 rounded-full bg-{{ $color }}-100 text-{{ $color }}-700">
                        {{ \App\Models\PipelineJob::STATUSES[$job->status]['label'] }}
                    </span>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Connector</p>
                        <p class="font-semibold text-gray-700">{{ $job->connector?->name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Triggered by</p>
                        <p class="font-semibold text-gray-700">{{ $job->triggeredBy?->name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Started</p>
                        <p class="font-semibold text-gray-700">{{ $job->started_at?->format('M j, H:i') ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Finished</p>
                        <p class="font-semibold text-gray-700">{{ $job->finished_at?->format('M j, H:i') ?? '—' }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-wrap gap-3">
                @if(!in_array($job->status, ['running']))
                <form method="POST" action="{{ route('tenant.jobs.run', ['tenantSlug' => $tenant->slug, 'job' => $job->id]) }}">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors">
                        ▶ Run Job
                    </button>
                </form>
                @endif

                @if(in_array($job->status, ['pending', 'running']))
                <form method="POST" action="{{ route('tenant.jobs.cancel', ['tenantSlug' => $tenant->slug, 'job' => $job->id]) }}">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-500 text-white text-sm font-semibold rounded-lg hover:bg-yellow-600 transition-colors">
                        Cancel
                    </button>
                </form>
                @endif

                <form method="POST" action="{{ route('tenant.jobs.destroy', ['tenantSlug' => $tenant->slug, 'job' => $job->id]) }}"
                      onsubmit="return confirm('Delete this job?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 border border-red-200 text-red-600 text-sm font-semibold rounded-lg hover:bg-red-50 transition-colors">
                        Delete
                    </button>
                </form>
            </div>

            <!-- Log output -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-700 mb-3">Log Output</h3>
                @if($job->logs)
                <pre class="bg-gray-900 text-green-400 rounded-lg p-4 text-xs font-mono overflow-x-auto whitespace-pre-wrap leading-relaxed">{{ $job->logs }}</pre>
                @else
                <p class="text-sm text-gray-400">No logs yet. Run the job to see output here.</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
