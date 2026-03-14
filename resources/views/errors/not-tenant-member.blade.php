<x-app-layout>
    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
                <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>

                <h2 class="text-lg font-bold text-gray-800 mb-1">Access Denied</h2>
                <p class="text-sm text-gray-500 mb-6">
                    You are not a member of <span class="font-semibold text-gray-700">{{ $tenant->name }}</span>.
                    Ask a workspace admin to send you an invite link.
                </p>

                <div class="space-y-2">
                    <a href="{{ route('dashboard') }}"
                       class="w-full flex justify-center py-2.5 px-4 rounded-lg text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                        Go to My Workspaces
                    </a>
                    <a href="{{ route('profile.edit') }}"
                       class="w-full flex justify-center py-2.5 px-4 rounded-lg border border-gray-200 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                        Manage Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex justify-center py-2.5 px-4 rounded-lg text-sm font-semibold text-gray-400 hover:text-gray-600 transition-colors">
                            Log Out
                        </button>
                    </form>
                </div>

                <p class="text-xs text-gray-400 mt-4">Logged in as {{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
