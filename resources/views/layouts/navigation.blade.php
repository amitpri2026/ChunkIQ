<nav x-data="{ open: false }" class="bg-[#001141] border-b border-white/10 sticky top-0 z-40">
    <div class="max-w-full px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-14">

            {{-- Left: Logo + nav links --}}
            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard') }}"
                   class="text-white font-black text-lg tracking-tight shrink-0 select-none">
                    Chunk<span class="text-[#4d9fff]">IQ</span>
                </a>

                <div class="hidden sm:flex items-center gap-1">
                    <a href="{{ route('dashboard') }}"
                       class="px-3 py-1.5 text-sm font-medium rounded transition-colors
                           {{ request()->routeIs('dashboard') ? 'text-white bg-white/15' : 'text-blue-200 hover:text-white hover:bg-white/10' }}">
                        Dashboard
                    </a>
                    @if(Auth::user()->is_super_admin)
                    <a href="{{ route('admin.dashboard') }}"
                       class="px-3 py-1.5 text-sm font-medium rounded transition-colors
                           {{ request()->routeIs('admin.*') ? 'text-white bg-white/15' : 'text-blue-200 hover:text-white hover:bg-white/10' }}">
                        Super Admin
                    </a>
                    <a href="{{ route('admin.tickets.index') }}"
                       class="px-3 py-1.5 text-sm font-medium rounded transition-colors
                           {{ request()->routeIs('admin.tickets.*') ? 'text-white bg-white/15' : 'text-blue-200 hover:text-white hover:bg-white/10' }}">
                        Tickets
                    </a>
                    @endif
                </div>
            </div>

            {{-- Right: Notifications + User --}}
            <div class="flex items-center gap-1">

                {{-- Notifications Bell --}}
                <div x-data="{
                        open: false,
                        unread: {{ Auth::check() ? Auth::user()->unreadNotifications()->count() : 0 }},
                        notifications: [],
                        async load() {
                            if (this.notifications.length) return;
                            const r = await fetch('/notifications', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
                            const d = await r.json();
                            this.notifications = d.notifications;
                            this.unread = d.unread;
                        },
                        async markAll() {
                            await fetch('/notifications/mark-all-read', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
                            this.unread = 0;
                            this.notifications.forEach(n => n.read_at = true);
                        }
                    }" class="relative">
                    <button @click="open = !open; if(open) load()"
                            class="relative p-2 rounded-lg text-blue-200 hover:text-white hover:bg-white/10 transition-colors focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span x-show="unread > 0" x-text="unread > 9 ? '9+' : unread"
                              class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[10px] min-w-[16px] h-4 px-0.5 rounded-full flex items-center justify-center font-bold leading-none"></span>
                    </button>

                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         @click.outside="open = false"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-2xl border border-gray-200 z-50 overflow-hidden"
                         style="display:none;">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50">
                            <span class="font-semibold text-sm text-gray-700">Notifications</span>
                            <button @click="markAll()" class="text-xs text-blue-500 hover:text-blue-700 font-medium">Mark all read</button>
                        </div>
                        <div class="max-h-96 overflow-y-auto divide-y divide-gray-50">
                            <template x-for="n in notifications" :key="n.id">
                                <div :class="n.read_at ? 'bg-white' : 'bg-blue-50'"
                                     class="px-4 py-3 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start gap-2">
                                        <span class="text-base mt-0.5" x-text="n.data.icon"></span>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-800 truncate" x-text="n.data.title"></p>
                                            <p class="text-xs text-gray-500 mt-0.5 truncate" x-text="n.data.body"></p>
                                            <p class="text-xs text-gray-400 mt-1" x-text="n.created_at"></p>
                                        </div>
                                        <span x-show="!n.read_at" class="w-2 h-2 bg-blue-500 rounded-full mt-1.5 flex-shrink-0"></span>
                                    </div>
                                </div>
                            </template>
                            <div x-show="notifications.length === 0" class="px-4 py-8 text-center text-gray-400 text-sm">
                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                No notifications yet
                            </div>
                        </div>
                    </div>
                </div>

                {{-- User Menu --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                            class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg text-blue-200 hover:text-white hover:bg-white/10 transition-colors focus:outline-none">
                        <div class="w-7 h-7 rounded-full bg-[#0f62fe] flex items-center justify-center text-xs font-bold text-white shrink-0 uppercase">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span class="text-sm font-medium hidden lg:block text-blue-100">{{ Auth::user()->name }}</span>
                        <svg class="w-3.5 h-3.5 hidden lg:block opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         @click.outside="open = false"
                         class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-2xl border border-gray-200 z-50 overflow-hidden py-1"
                         style="display:none;">
                        <div class="px-4 py-2.5 border-b border-gray-100 bg-gray-50">
                            <p class="text-xs font-bold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <a href="{{ route('tickets.index') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                            My Tickets
                        </a>
                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profile
                        </a>
                        <div class="border-t border-gray-100 mt-1 pt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="flex items-center gap-2.5 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Mobile hamburger --}}
                <button @click="open = !open"
                        class="sm:hidden p-2 rounded-lg text-blue-200 hover:text-white hover:bg-white/10 transition-colors ml-1">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden border-t border-white/10 bg-[#001141]">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('dashboard') }}"
               class="block px-3 py-2 rounded text-sm font-medium
                   {{ request()->routeIs('dashboard') ? 'text-white bg-white/15' : 'text-blue-200 hover:text-white hover:bg-white/10' }}">
                Dashboard
            </a>
            @if(Auth::user()->is_super_admin)
            <a href="{{ route('admin.dashboard') }}"
               class="block px-3 py-2 rounded text-sm font-medium
                   {{ request()->routeIs('admin.*') ? 'text-white bg-white/15' : 'text-blue-200 hover:text-white hover:bg-white/10' }}">
                Super Admin
            </a>
            @endif
        </div>
        <div class="px-4 py-3 border-t border-white/10 space-y-1">
            <div class="flex items-center gap-2.5 px-3 py-2">
                <div class="w-7 h-7 rounded-full bg-[#0f62fe] flex items-center justify-center text-xs font-bold text-white uppercase">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-blue-300">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}"
               class="block px-3 py-2 rounded text-sm font-medium text-blue-200 hover:text-white hover:bg-white/10">
                Profile
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left px-3 py-2 rounded text-sm font-medium text-red-300 hover:text-red-100 hover:bg-white/10">
                    Sign Out
                </button>
            </form>
        </div>
    </div>
</nav>
