<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold px-2.5 py-1 bg-red-100 text-red-700 rounded-full uppercase tracking-wide">Super Admin</span>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Platform Settings — Function App</h2>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-400 hover:text-gray-600">← Dashboard</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            {{-- Info banner --}}
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-5">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm text-amber-800">
                        <p class="font-semibold mb-1">These settings are platform-wide (not per tenant).</p>
                        <p>The Function App URL and Key belong to <strong>ChunkIQ's own Azure infrastructure</strong> — users never see or configure these.
                        All tenant jobs are routed through this single Function App endpoint.</p>
                        <p class="mt-2">Find the Function Key in: <span class="font-mono bg-amber-100 px-1.5 py-0.5 rounded text-xs">Azure Portal → Function App → Functions → run_pipeline_http → Function Keys → default</span></p>
                    </div>
                </div>
            </div>

            {{-- Settings Form --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
                    <h3 class="text-white font-bold text-base">Azure Function App Credentials</h3>
                    <p class="text-red-100 text-xs mt-0.5">Stored encrypted. Applied to every tenant's pipeline job trigger.</p>
                </div>

                <form method="POST" action="{{ route('admin.system-config.update') }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    @foreach($configs as $key => $config)
                    <div>
                        <label for="{{ $key }}" class="block text-sm font-semibold text-gray-700 mb-1">
                            {{ $config['label'] }}
                        </label>
                        <p class="text-xs text-gray-500 mb-2">{{ $config['description'] }}</p>
                        <div class="relative">
                            <input
                                type="password"
                                id="{{ $key }}"
                                name="{{ $key }}"
                                placeholder="{{ $config['masked'] ?? $config['placeholder'] }}"
                                autocomplete="off"
                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 text-sm pr-10"
                            >
                            <button type="button" onclick="toggleVisible('{{ $key }}')"
                                class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                tabindex="-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        @if($config['masked'])
                            <p class="text-xs text-gray-400 mt-1">
                                Current: <span class="font-mono">{{ $config['masked'] }}</span>
                                &mdash; leave blank to keep existing value
                            </p>
                        @else
                            <p class="text-xs text-red-400 mt-1 font-medium">⚠ Not yet configured — jobs will fail until this is set.</p>
                        @endif
                        <x-input-error :messages="$errors->get($key)" class="mt-1" />
                    </div>
                    @endforeach

                    <div class="pt-4 border-t border-gray-100 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>

            <p class="text-center text-xs text-gray-400">Values are AES-256 encrypted at rest in the platform database.</p>
        </div>
    </div>

    <script>
        function toggleVisible(id) {
            const el = document.getElementById(id);
            el.type = el.type === 'password' ? 'text' : 'password';
        }
    </script>
</x-app-layout>
