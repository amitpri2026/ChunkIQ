<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold px-2.5 py-1 bg-red-100 text-red-700 rounded-full uppercase tracking-wide">Super Admin</span>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Subscription Requests</h2>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-400 hover:text-gray-600">← Overview</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>
            @endif

            @if($requests->isEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-10 text-center text-gray-400 text-sm">
                    No subscription requests yet.
                </div>
            @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 divide-y divide-gray-100">
                @foreach($requests as $req)
                @php $color = $req->statusColor(); @endphp
                <div class="flex items-center justify-between p-4">
                    <div>
                        <div class="flex items-center gap-2 mb-0.5">
                            <p class="font-semibold text-gray-800 text-sm">{{ $req->name }}</p>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                                bg-{{ $color }}-100 text-{{ $color }}-700">
                                {{ $req->planLabel() }}
                            </span>
                            <span class="text-xs px-2 py-0.5 rounded-full border
                                border-{{ $color }}-200 text-{{ $color }}-600">
                                {{ $req->statusLabel() }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-400">
                            {{ $req->email }}
                            @if($req->company) · {{ $req->company }} @endif
                            · {{ $req->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <a href="{{ route('admin.subscriptions.show', $req->id) }}"
                       class="text-xs text-blue-600 hover:text-blue-800 font-semibold">View →</a>
                </div>
                @endforeach
            </div>
            <div>{{ $requests->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
