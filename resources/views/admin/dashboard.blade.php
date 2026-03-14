<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="text-xs font-bold px-2.5 py-1 bg-red-100 text-red-700 rounded-full uppercase tracking-wide">Super Admin</span>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Platform Overview</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>
            @endif

            <!-- Platform stats -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                @foreach([
                    ['label' => 'Tenants',        'value' => $stats['tenants'],  'color' => 'blue'],
                    ['label' => 'Users',           'value' => $stats['users'],    'color' => 'indigo'],
                    ['label' => 'Total Jobs',      'value' => $stats['jobs'],     'color' => 'gray'],
                    ['label' => 'Running',         'value' => $stats['running'],  'color' => 'green'],
                    ['label' => 'Failed',          'value' => $stats['failed'],   'color' => 'red'],
                ] as $stat)
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">{{ $stat['label'] }}</p>
                    <p class="text-3xl font-extrabold text-{{ $stat['color'] }}-600">{{ $stat['value'] }}</p>
                </div>
                @endforeach
            </div>

            <!-- Nav -->
            <div class="flex gap-3">
                <a href="{{ route('admin.tenants') }}"
                   class="px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-lg hover:border-blue-400 transition-colors">
                    All Tenants
                </a>
                <a href="{{ route('admin.users') }}"
                   class="px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-lg hover:border-blue-400 transition-colors">
                    All Users
                </a>
            </div>

            <!-- Recent Jobs -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="font-bold text-gray-700 mb-4">Recent Jobs (all tenants)</h2>
                @if($recentJobs->isEmpty())
                    <p class="text-sm text-gray-400">No jobs yet.</p>
                @else
                <div class="divide-y divide-gray-100">
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
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="font-bold text-gray-700 mb-4">Recently Created Workspaces</h2>
                <div class="divide-y divide-gray-100">
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
