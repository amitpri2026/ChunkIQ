<div x-data="{ open: false }" class="fixed bottom-6 right-6 z-40">
    <!-- Floating button -->
    <button @click="open = !open"
            class="w-13 h-13 w-12 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-xl flex items-center justify-center transition-all duration-200 hover:scale-110"
            title="Help & Support">
        <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <svg x-show="open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <!-- Panel -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
         @click.outside="open = false"
         class="absolute bottom-16 right-0 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden"
         style="display:none;">

        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-5 py-4 text-white">
            <h3 class="font-bold text-base">Help & Support</h3>
            <p class="text-blue-200 text-xs mt-0.5">How can we help you today?</p>
        </div>

        <!-- Quick links -->
        <div class="p-4">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Quick Links</p>
            <div class="space-y-2">
                <a href="https://docs.chunkiq.com" target="_blank"
                   class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:border-blue-200 hover:bg-blue-50 transition-all group">
                    <span class="text-xl">📖</span>
                    <div>
                        <p class="text-sm font-semibold text-gray-700 group-hover:text-blue-700">Documentation</p>
                        <p class="text-xs text-gray-400">Setup guides & API reference</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 ml-auto group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="https://docs.chunkiq.com/faq" target="_blank"
                   class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:border-blue-200 hover:bg-blue-50 transition-all group">
                    <span class="text-xl">❓</span>
                    <div>
                        <p class="text-sm font-semibold text-gray-700 group-hover:text-blue-700">FAQ</p>
                        <p class="text-xs text-gray-400">Common questions answered</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 ml-auto group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <!-- Contact support -->
        <div class="px-4 pb-4 border-t border-gray-100 pt-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Contact Support</p>
            <a href="{{ route('tickets.create') }}" @click="open = false"
               class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-blue-600 text-white font-semibold text-sm rounded-xl hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create Support Ticket
            </a>
            <a href="{{ route('tickets.index') }}" @click="open = false"
               class="mt-2 flex items-center justify-center gap-2 w-full px-4 py-2.5 border border-gray-200 text-gray-600 font-semibold text-sm rounded-xl hover:border-gray-300 hover:bg-gray-50 transition-colors">
                View My Tickets
            </a>
        </div>
    </div>
</div>
