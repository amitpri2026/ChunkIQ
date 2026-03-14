<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $tenant->name }} — Azure Configuration
            </h2>
            <a href="{{ route('tenant.dashboard', ['tenantSlug' => $tenant->slug]) }}"
               class="text-sm text-gray-400 hover:text-gray-600">← Dashboard</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>
            @endif

            <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 text-sm text-blue-700">
                These values are stored encrypted and map directly to the Azure credentials used by your ChunkIQ_Cloud jobs.
                Leave a field blank to keep the existing value.
            </div>

            <form method="POST" action="{{ route('tenant.config.update', ['tenantSlug' => $tenant->slug]) }}"
                  class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">
                @csrf

                @foreach($configs as $key => $config)
                <div>
                    <label for="{{ $key }}" class="block text-sm font-semibold text-gray-700 mb-1">
                        {{ $config['label'] }}
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            id="{{ $key }}"
                            name="{{ $key }}"
                            placeholder="{{ $config['masked'] ?? 'Not set' }}"
                            class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm pr-10"
                        >
                        <button type="button" onclick="toggleVisible('{{ $key }}')"
                            class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @if($config['masked'])
                        <p class="text-xs text-gray-400 mt-1">Current: <span class="font-mono">{{ $config['masked'] }}</span></p>
                    @endif
                    <x-input-error :messages="$errors->get($key)" class="mt-1" />
                </div>
                @endforeach

                <div class="pt-2 flex justify-end">
                    <x-primary-button>Save Configuration</x-primary-button>
                </div>
            </form>

        </div>
    </div>

    <script>
        function toggleVisible(id) {
            const el = document.getElementById(id);
            el.type = el.type === 'password' ? 'text' : 'password';
        }
    </script>
</x-app-layout>
