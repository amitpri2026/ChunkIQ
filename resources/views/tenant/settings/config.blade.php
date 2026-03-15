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

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash message --}}
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            {{-- Step Progress Bar --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-5">
                <div class="overflow-x-auto">
                    <nav class="flex items-center min-w-max">
                        @php $steps = App\Http\Controllers\Tenant\TenantConfigController::STEPS; @endphp
                        @foreach($steps as $num => $s)
                        @php
                            $done    = in_array($num, $completedSteps);
                            $current = $num === $currentStep;
                            $last    = $num === count($steps);
                        @endphp

                        {{-- Step bubble --}}
                        <a href="{{ route('tenant.config.edit', ['tenantSlug' => $tenant->slug, 'step' => $num]) }}"
                           class="flex flex-col items-center group flex-shrink-0">
                            <span @class([
                                'w-9 h-9 flex items-center justify-center rounded-full text-xs font-bold transition-all',
                                'bg-blue-600 text-white ring-4 ring-blue-100' => $current,
                                'bg-green-500 text-white'                     => $done && !$current,
                                'border-2 border-gray-300 text-gray-400 group-hover:border-blue-400 group-hover:text-blue-500' => !$current && !$done,
                            ])>
                                @if($done && !$current)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    {{ $num }}
                                @endif
                            </span>
                            <span @class([
                                'mt-1.5 text-[10px] font-medium text-center leading-tight whitespace-nowrap',
                                'text-blue-600'  => $current,
                                'text-green-600' => $done && !$current,
                                'text-gray-400'  => !$current && !$done,
                            ])>{{ $s['title'] }}</span>
                        </a>

                        {{-- Connector line between steps --}}
                        @if(!$last)
                        <div @class([
                            'h-0.5 w-10 mx-1 mt-[-14px] flex-shrink-0',
                            'bg-green-400' => $done,
                            'bg-gray-200'  => !$done,
                        ])></div>
                        @endif

                        @endforeach
                    </nav>
                </div>
            </div>

            {{-- Current Step Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

                {{-- Step Header --}}
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div class="bg-white/20 rounded-lg w-10 h-10 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                            {{ $currentStep }}
                        </div>
                        <div>
                            <p class="text-blue-200 text-xs font-medium uppercase tracking-wider">
                                Step {{ $currentStep }} of {{ $totalSteps }}
                            </p>
                            <h3 class="text-white text-lg font-bold leading-tight">{{ $step['title'] }}</h3>
                        </div>
                    </div>
                    <p class="mt-2 text-blue-100 text-sm leading-relaxed">{{ $step['description'] }}</p>
                </div>

                <div class="p-6 space-y-6">

                    {{-- Setup Instructions --}}
                    @if(!empty($step['setup_steps']))
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-amber-800 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            How to set this up
                        </h4>
                        <ol class="space-y-1.5 list-none">
                            @foreach($step['setup_steps'] as $i => $instruction)
                            <li class="flex items-start gap-2 text-xs text-amber-800">
                                <span class="bg-amber-200 text-amber-700 rounded-full w-4 h-4 flex items-center justify-center text-[10px] font-bold flex-shrink-0 mt-0.5">{{ $i + 1 }}</span>
                                {{ $instruction }}
                            </li>
                            @endforeach
                        </ol>
                    </div>
                    @endif

                    {{-- Required Permissions --}}
                    @if(!empty($step['permissions']))
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-blue-800 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Required Microsoft Graph API Permissions
                        </h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-xs">
                                <thead>
                                    <tr class="border-b border-blue-200">
                                        <th class="text-left pb-2 text-blue-700 font-semibold pr-6">Permission</th>
                                        <th class="text-left pb-2 text-blue-700 font-semibold pr-6">Type</th>
                                        <th class="text-left pb-2 text-blue-700 font-semibold">Purpose</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-blue-100">
                                    @foreach($step['permissions'] as $perm)
                                    <tr>
                                        <td class="py-2 pr-6 font-mono text-blue-900 font-semibold">{{ $perm['name'] }}</td>
                                        <td class="py-2 pr-6">
                                            <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-medium">{{ $perm['type'] }}</span>
                                        </td>
                                        <td class="py-2 text-blue-700">{{ $perm['reason'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    {{-- Fields --}}
                    <form method="POST" action="{{ route('tenant.config.update', ['tenantSlug' => $tenant->slug]) }}">
                        @csrf
                        <input type="hidden" name="current_step" value="{{ $currentStep }}">

                        <div class="space-y-5">
                            @foreach($step['keys'] as $key)
                            @php $config = $configs[$key]; @endphp
                            <div>
                                <label for="{{ $key }}" class="block text-sm font-semibold text-gray-700 mb-1">
                                    {{ $config['label'] }}
                                </label>
                                <div class="relative">
                                    <input
                                        type="password"
                                        id="{{ $key }}"
                                        name="{{ $key }}"
                                        placeholder="{{ $config['masked'] ?? 'Enter value…' }}"
                                        autocomplete="off"
                                        class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm pr-10"
                                    >
                                    <button type="button" onclick="toggleVisible('{{ $key }}')"
                                        class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                        tabindex="-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                                @if($config['masked'])
                                    <p class="text-xs text-gray-400 mt-1">
                                        Current: <span class="font-mono">{{ $config['masked'] }}</span>
                                        &mdash; leave blank to keep existing value
                                    </p>
                                @else
                                    <p class="text-xs text-gray-400 mt-1">Not yet configured</p>
                                @endif
                                <x-input-error :messages="$errors->get($key)" class="mt-1" />
                            </div>
                            @endforeach
                        </div>

                        {{-- Navigation --}}
                        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                            <div>
                                @if($currentStep > 1)
                                <a href="{{ route('tenant.config.edit', ['tenantSlug' => $tenant->slug, 'step' => $currentStep - 1]) }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                    ← Back
                                </a>
                                @endif
                            </div>

                            <div class="flex items-center gap-3">
                                @if($currentStep < $totalSteps)
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                    Save &amp; Next
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                                @else
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Save &amp; Finish
                                </button>
                                @endif
                            </div>
                        </div>
                    </form>

                </div>
            </div>

            {{-- Footer note --}}
            <p class="text-center text-xs text-gray-400">
                All values are AES-256 encrypted at rest. You can revisit any step at any time.
            </p>

        </div>
    </div>

    <script>
        function toggleVisible(id) {
            const el = document.getElementById(id);
            el.type = el.type === 'password' ? 'text' : 'password';
        }
    </script>
</x-app-layout>
