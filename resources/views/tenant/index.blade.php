<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Workspaces</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome -->
            <div class="bg-gradient-to-r from-blue-700 to-blue-500 rounded-xl p-6 text-white shadow">
                <p class="text-sm font-semibold uppercase tracking-widest text-blue-200 mb-1">Welcome back</p>
                <h1 class="text-2xl font-bold">{{ Auth::user()->name }}</h1>
                <p class="text-blue-200 mt-1 text-sm">Select a workspace to continue or create a new one.</p>
            </div>

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Tenant list -->
            @if($tenants->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="font-bold text-gray-700 mb-4">Your Workspaces</h2>
                <div class="space-y-3">
                    @foreach($tenants as $tenant)
                    <div class="flex items-center justify-between p-4 rounded-lg border border-gray-100 hover:border-blue-200 hover:bg-blue-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                <span class="font-bold text-blue-600 text-sm uppercase">{{ substr($tenant->name, 0, 2) }}</span>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $tenant->name }}</p>
                                <p class="text-xs text-gray-400">{{ $tenant->slug }}.chunkiq.com</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-semibold px-2 py-1 rounded-full
                                {{ $tenant->pivot->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($tenant->pivot->role) }}
                            </span>
                            <a href="{{ $tenant->url('dashboard') }}"
                               class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                                Open
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-10 text-center">
                <div class="w-14 h-14 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-700 mb-1">No workspaces yet</h3>
                <p class="text-sm text-gray-400 mb-5">Create your first workspace or ask someone to send you an invite link.</p>
                <a href="{{ route('tenants.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    Create a workspace
                </a>
            </div>
            @endif

            <!-- Actions -->
            <div class="flex gap-3">
                <a href="{{ route('tenants.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    + New Workspace
                </a>
                <a href="{{ route('profile.edit') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-lg hover:border-gray-300 transition-colors">
                    My Profile
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
