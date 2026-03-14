<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $tenant->name }} — Members
            </h2>
            <a href="{{ route('tenant.dashboard', ['tenantSlug' => $tenant->slug]) }}"
               class="text-sm text-gray-400 hover:text-gray-600">← Dashboard</a>
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

            <!-- Invite link generator -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-700 mb-4">Generate Invite Link</h3>
                <form method="POST" action="{{ route('tenant.invites.store', ['tenantSlug' => $tenant->slug]) }}"
                      class="flex items-end gap-3">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select name="role"
                            class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors whitespace-nowrap">
                        Generate Link
                    </button>
                </form>

                @if(session('invite_link'))
                <div class="mt-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="text-xs font-semibold text-gray-500 mb-1">
                        Invite link ({{ ucfirst(session('invite_role')) }}) — expires in 7 days
                    </p>
                    <div class="flex items-center gap-2">
                        <code class="text-xs text-blue-700 break-all flex-1">{{ session('invite_link') }}</code>
                        <button onclick="navigator.clipboard.writeText('{{ session('invite_link') }}')"
                            class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors whitespace-nowrap">
                            Copy
                        </button>
                    </div>
                </div>
                @endif
            </div>

            <!-- Members list -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-700 mb-4">Members ({{ $members->count() }})</h3>
                <div class="space-y-2">
                    @foreach($members as $member)
                    <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-xs font-bold text-blue-600">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                            </div>
                            <div>
                                <p class="font-semibold text-sm text-gray-800">
                                    {{ $member->name }}
                                    @if($tenant->owner_id === $member->id)
                                        <span class="text-xs font-normal text-gray-400 ml-1">(owner)</span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-400">{{ $member->email }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            @if($tenant->owner_id !== $member->id && $member->id !== Auth::id())
                                <!-- Change role -->
                                <form method="POST" action="{{ route('tenant.members.role', ['tenantSlug' => $tenant->slug, 'user' => $member->id]) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="role" value="{{ $member->pivot->role === 'admin' ? 'user' : 'admin' }}">
                                    <button type="submit"
                                        class="text-xs px-3 py-1 border rounded-full transition-colors
                                        {{ $member->pivot->role === 'admin'
                                            ? 'border-purple-200 text-purple-700 hover:bg-purple-50'
                                            : 'border-gray-200 text-gray-600 hover:bg-gray-100' }}">
                                        {{ $member->pivot->role === 'admin' ? 'Make User' : 'Make Admin' }}
                                    </button>
                                </form>
                                <!-- Remove -->
                                <form method="POST" action="{{ route('tenant.members.remove', ['tenantSlug' => $tenant->slug, 'user' => $member->id]) }}"
                                      onsubmit="return confirm('Remove {{ $member->name }} from this workspace?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs px-3 py-1 border border-red-200 text-red-600 rounded-full hover:bg-red-50 transition-colors">
                                        Remove
                                    </button>
                                </form>
                            @else
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                    {{ $member->pivot->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($member->pivot->role) }}
                                </span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
