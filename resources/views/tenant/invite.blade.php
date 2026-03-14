<x-guest-layout>
    <div class="text-center mb-6">
        <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-800">You've been invited</h2>
        <p class="text-sm text-gray-500 mt-1">
            Join <span class="font-semibold text-gray-700">{{ $invite->tenant->name }}</span>
            as <span class="font-semibold text-{{ $invite->role === 'admin' ? 'purple' : 'blue' }}-600">{{ ucfirst($invite->role) }}</span>
        </p>
    </div>

    @if($errors->has('invite'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm mb-4">
            {{ $errors->first('invite') }}
        </div>
    @endif

    @auth
        <form method="POST" action="{{ route('invite.accept', $invite->token) }}">
            @csrf
            <button type="submit"
                class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                Accept Invitation
            </button>
        </form>
        <p class="text-center text-xs text-gray-400 mt-3">Joining as {{ Auth::user()->email }}</p>
    @else
        <p class="text-sm text-gray-600 text-center mb-4">
            You need to be logged in to accept this invitation.
        </p>
        <div class="space-y-2">
            <a href="{{ route('login') }}?redirect={{ urlencode(url()->current()) }}"
               class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                Log in to accept
            </a>
            <a href="{{ route('register') }}?redirect={{ urlencode(url()->current()) }}"
               class="w-full flex justify-center py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                Create an account
            </a>
        </div>
    @endauth

    <p class="text-center text-xs text-gray-400 mt-4">
        Invite expires {{ $invite->expires_at->diffForHumans() }}
    </p>
</x-guest-layout>
