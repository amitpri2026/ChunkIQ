<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold px-2.5 py-1 bg-red-100 text-red-700 rounded-full uppercase tracking-wide">Super Admin</span>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Subscription Request</h2>
            </div>
            <a href="{{ route('admin.subscriptions.index') }}" class="text-sm text-gray-400 hover:text-gray-600">← Requests</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>
            @endif

            <!-- Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
                <div class="flex items-center gap-3">
                    @php $color = $subscription->statusColor(); @endphp
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full
                        {{ $subscription->plan === 'enterprise' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ $subscription->planLabel() }} Plan
                    </span>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full border
                        border-{{ $color }}-200 text-{{ $color }}-600 bg-{{ $color }}-50">
                        {{ $subscription->statusLabel() }}
                    </span>
                    <span class="text-xs text-gray-400 ml-auto">{{ $subscription->created_at->format('M j, Y H:i') }}</span>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Name</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $subscription->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Email</p>
                        <p class="text-sm text-gray-800">
                            <a href="mailto:{{ $subscription->email }}" class="text-blue-600 hover:underline">{{ $subscription->email }}</a>
                        </p>
                    </div>
                    @if($subscription->company)
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Company</p>
                        <p class="text-sm text-gray-800">{{ $subscription->company }}</p>
                    </div>
                    @endif
                    @if($subscription->user)
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Registered user</p>
                        <p class="text-sm text-gray-800">{{ $subscription->user->name }} ({{ $subscription->user->email }})</p>
                    </div>
                    @endif
                </div>

                @if($subscription->message)
                <div>
                    <p class="text-xs text-gray-400 mb-1">Message</p>
                    <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3 leading-relaxed">{{ $subscription->message }}</p>
                </div>
                @endif
            </div>

            <!-- Update status + assign workspace -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-700 mb-4">Update Request</h3>
                <form method="POST" action="{{ route('admin.subscriptions.status', $subscription->id) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Status</label>
                        <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            @foreach(\App\Models\SubscriptionRequest::STATUSES as $key => $meta)
                            <option value="{{ $key }}" @selected($subscription->status === $key)>
                                {{ $meta['label'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Link to workspace (optional)</label>
                        <select name="tenant_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">— None —</option>
                            @foreach(\App\Models\Tenant::orderBy('name')->get() as $tenant)
                            <option value="{{ $tenant->id }}" @selected($subscription->tenant_id === $tenant->id)>
                                {{ $tenant->name }} ({{ $tenant->slug }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-colors">
                        Save changes
                    </button>
                </form>
            </div>

            @if($subscription->tenant)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Linked workspace</p>
                    <p class="font-semibold text-gray-800 text-sm">{{ $subscription->tenant->name }}</p>
                    <p class="text-xs text-gray-400">Plan: {{ $subscription->tenant->planLabel() }}</p>
                </div>
                <a href="{{ route('admin.tenants.show', $subscription->tenant->id) }}"
                   class="text-xs text-blue-600 hover:text-blue-800 font-semibold">Manage workspace →</a>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
