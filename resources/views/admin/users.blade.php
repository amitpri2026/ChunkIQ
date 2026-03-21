<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold px-2.5 py-1 bg-red-100 text-red-700 rounded-full uppercase tracking-wide">Super Admin</span>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">All Users</h2>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-400 hover:text-gray-600">← Overview</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 divide-y divide-gray-100">
                @foreach($users as $user)
                <div class="flex items-center justify-between p-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <p class="font-semibold text-gray-800 text-sm">{{ $user->name }}</p>
                            @if($user->is_super_admin)
                                <span class="text-xs font-bold px-2 py-0.5 bg-red-100 text-red-700 rounded-full">Super Admin</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-400">{{ $user->email }} · {{ $user->tenants_count }} workspace{{ $user->tenants_count !== 1 ? 's' : '' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @unless($user->is_super_admin)
                        <form method="POST" action="{{ route('admin.impersonate', $user->id) }}" class="inline">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-full hover:bg-blue-100 transition-colors"
                                    onclick="return confirm('Login as {{ addslashes($user->name) }}?')">
                                👤 Login as User
                            </button>
                        </form>
                        @endunless
                        <form method="POST" action="{{ route('admin.users.toggle-superadmin', $user->id) }}">
                            @csrf
                            <button type="submit"
                                class="text-xs px-3 py-1 border rounded-full transition-colors
                                    {{ $user->is_super_admin
                                        ? 'border-red-200 text-red-600 hover:bg-red-50'
                                        : 'border-gray-200 text-gray-500 hover:bg-gray-50' }}">
                                {{ $user->is_super_admin ? 'Revoke Super Admin' : 'Make Super Admin' }}
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            <div>{{ $users->links() }}</div>

        </div>
    </div>
</x-app-layout>
