<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold px-2.5 py-1 bg-red-100 text-red-700 rounded-full uppercase tracking-wide">Super Admin</span>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $tenant->name }}</h2>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('tenants.open', $tenant->id) }}"
                   class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-white bg-indigo-600 rounded-full hover:bg-indigo-700 transition-colors">
                    Enter Workspace →
                </a>
                <a href="{{ route('admin.tenants') }}" class="text-sm text-gray-400 hover:text-gray-600">← Workspaces</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>
            @endif

            <!-- Tenant info -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <p class="text-xs text-gray-400 mb-1">Subdomain</p>
                    <p class="font-mono font-semibold text-gray-800 text-sm">{{ $tenant->slug }}.chunkiq.com</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <p class="text-xs text-gray-400 mb-1">Owner</p>
                    <p class="font-semibold text-gray-800 text-sm">{{ $tenant->owner->name }}</p>
                    <p class="text-xs text-gray-400">{{ $tenant->owner->email }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <p class="text-xs text-gray-400 mb-1">Plan</p>
                    @php $planColor = $tenant->planColor(); @endphp
                    <span class="inline-flex items-center text-xs font-bold px-2.5 py-1 rounded-full
                        bg-{{ $planColor }}-100 text-{{ $planColor }}-700">
                        {{ $tenant->planLabel() }}
                    </span>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <p class="text-xs text-gray-400 mb-1">Documents processed</p>
                    <p class="font-semibold text-gray-800 text-sm">
                        {{ number_format($tenant->documents_processed) }}
                        @if($tenant->documentLimit() !== null)
                            / {{ number_format($tenant->documentLimit()) }}
                        @else
                            <span class="text-xs text-gray-400">unlimited</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Plan Management -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-700 mb-4">Plan &amp; Limits</h3>
                <form method="POST" action="{{ route('admin.tenants.plan.update', $tenant->id) }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Plan</label>
                        <select name="plan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            @foreach(\App\Models\Tenant::PLANS as $key => $meta)
                            <option value="{{ $key }}" @selected($tenant->plan === $key)>
                                {{ $meta['name'] }}
                                @if($meta['price'] === 0) (Free)
                                @elseif($meta['price'] !== null) (${{ $meta['price'] }}/mo)
                                @else (Custom)
                                @endif
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Connector limit override</label>
                        <input type="number" name="connector_limit_override" min="0"
                               value="{{ $tenant->connector_limit_override }}"
                               placeholder="Leave blank = plan default"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <p class="text-xs text-gray-400 mt-0.5">Plan default: {{ $tenant->connectorLimit() ?? '∞' }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Document limit override</label>
                        <input type="number" name="document_limit_override" min="0"
                               value="{{ $tenant->document_limit_override }}"
                               placeholder="Leave blank = plan default"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <p class="text-xs text-gray-400 mt-0.5">Plan default: {{ $tenant->documentLimit() !== null ? number_format($tenant->documentLimit()) : '∞' }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Documents processed (manual)</label>
                        <input type="number" name="documents_processed" min="0"
                               value="{{ $tenant->documents_processed }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <p class="text-xs text-gray-400 mt-0.5">Used against document limit</p>
                    </div>

                    <div class="sm:col-span-2 lg:col-span-4 flex items-center gap-4">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-colors">
                            Update plan &amp; limits
                        </button>
                        <div class="text-xs text-gray-400">
                            Scheduled jobs:
                            <span class="{{ $tenant->allowsScheduledJobs() ? 'text-green-600 font-semibold' : 'text-gray-400' }}">
                                {{ $tenant->allowsScheduledJobs() ? '✓ Enabled' : '— Disabled' }}
                            </span>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Members -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-700 mb-3">Members ({{ $tenant->users->count() }})</h3>
                <div class="divide-y divide-gray-200">
                    @foreach($tenant->users as $user)
                    <div class="flex items-center justify-between py-2">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $user->email }}</p>
                        </div>
                        <span class="text-xs font-semibold px-2 py-1 rounded-full
                            {{ $user->pivot->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($user->pivot->role) }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Connectors -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-700 mb-3">Connectors ({{ $tenant->connectors->count() }})</h3>
                @if($tenant->connectors->isEmpty())
                    <p class="text-sm text-gray-400">None configured.</p>
                @else
                <div class="divide-y divide-gray-200">
                    @foreach($tenant->connectors as $connector)
                    <div class="flex items-center justify-between py-2">
                        <p class="text-sm font-semibold text-gray-800">{{ $connector->name }}</p>
                        <span class="text-xs text-gray-500">{{ $connector->getTypeLabel() }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Jobs -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-700 mb-3">Pipeline Jobs</h3>
                @if($jobs->isEmpty())
                    <p class="text-sm text-gray-400">No jobs yet.</p>
                @else
                <div class="divide-y divide-gray-200">
                    @foreach($jobs as $job)
                    @php $color = $job->getStatusBadgeColor(); @endphp
                    <div class="flex items-center justify-between py-3">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $job->name }}</p>
                            <p class="text-xs text-gray-400">
                                {{ \App\Models\PipelineJob::TYPES[$job->type] }}
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
                <div class="mt-3">{{ $jobs->links() }}</div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
