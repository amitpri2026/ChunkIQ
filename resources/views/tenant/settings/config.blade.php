<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-gray-900 leading-tight">Azure Configuration</h2>
                    <p class="text-xs text-gray-500">{{ $tenant->name }}</p>
                </div>
            </div>
            <a href="{{ route('tenant.dashboard', ['tenantSlug' => $tenant->slug]) }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash --}}
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
            @endif

            {{-- Step progress bar --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-6 py-5">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Setup Progress</span>
                    <span class="text-xs text-gray-400">— {{ count($completedSteps) }} of {{ $totalSteps }} steps complete</span>
                </div>
                <nav class="flex items-center">
                    @php $steps = App\Http\Controllers\Tenant\TenantConfigController::STEPS; @endphp
                    @foreach($steps as $num => $s)
                    @php
                        $done    = in_array($num, $completedSteps);
                        $current = $num === $currentStep;
                        $last    = $num === count($steps);
                    @endphp
                    <a href="{{ route('tenant.config.edit', ['tenantSlug' => $tenant->slug, 'step' => $num]) }}"
                       class="flex flex-col items-center group shrink-0">
                        <span @class([
                            'w-9 h-9 flex items-center justify-center rounded-full text-xs font-bold transition-all',
                            'bg-[#0f62fe] text-white ring-4 ring-blue-100' => $current,
                            'bg-green-500 text-white'                      => $done && !$current,
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
                            'mt-1.5 text-[10px] font-semibold text-center leading-tight whitespace-nowrap',
                            'text-[#0f62fe]' => $current,
                            'text-green-600' => $done && !$current,
                            'text-gray-400'  => !$current && !$done,
                        ])>{{ $s['title'] }}</span>
                    </a>
                    @if(!$last)
                    <div @class([
                        'h-0.5 flex-1 mx-2 mt-[-14px]',
                        'bg-green-400' => $done,
                        'bg-gray-200'  => !$done,
                    ])></div>
                    @endif
                    @endforeach
                </nav>
            </div>

            {{-- Two-column layout: form (left) + guide (right) --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">

                {{-- LEFT — Form --}}
                <div class="lg:col-span-3 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

                    {{-- Step header --}}
                    <div class="bg-[#001141] px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div class="bg-white/20 rounded-lg w-10 h-10 flex items-center justify-center text-white font-bold text-lg shrink-0">
                                {{ $currentStep }}
                            </div>
                            <div>
                                <p class="text-blue-300 text-xs font-semibold uppercase tracking-wider">
                                    Step {{ $currentStep }} of {{ $totalSteps }}
                                </p>
                                <h3 class="text-white text-lg font-bold leading-tight">{{ $step['title'] }}</h3>
                            </div>
                        </div>
                        <p class="mt-2 text-blue-200 text-sm leading-relaxed">{{ $step['description'] }}</p>
                    </div>

                    <div class="p-6">
                        <form method="POST" action="{{ route('tenant.config.update', ['tenantSlug' => $tenant->slug]) }}">
                            @csrf
                            <input type="hidden" name="current_step" value="{{ $currentStep }}">

                            <div class="space-y-5">
                                @foreach($step['keys'] as $key)
                                @php $config = $configs[$key]; @endphp
                                <div>
                                    <label for="{{ $key }}" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        {{ $config['label'] }}
                                    </label>
                                    <div class="relative">
                                        <input
                                            type="password"
                                            id="{{ $key }}"
                                            name="{{ $key }}"
                                            placeholder="{{ $config['masked'] ?? 'Enter value…' }}"
                                            autocomplete="off"
                                            class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#0f62fe] focus:border-[#0f62fe] text-sm pr-10"
                                        >
                                        <button type="button" onclick="toggleVisible('{{ $key }}')"
                                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                            tabindex="-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                    </div>
                                    @if($config['masked'])
                                        <p class="text-xs text-gray-400 mt-1">
                                            Saved: <span class="font-mono text-gray-500">{{ $config['masked'] }}</span>
                                            &mdash; leave blank to keep
                                        </p>
                                    @else
                                        <p class="text-xs text-gray-400 mt-1">Not yet configured</p>
                                    @endif
                                    <x-input-error :messages="$errors->get($key)" class="mt-1" />
                                </div>
                                @endforeach
                            </div>

                            {{-- Actions --}}
                            @php
                                $testRoutes = [
                                    1 => route('tenant.config.test.app-registration', ['tenantSlug' => $tenant->slug]),
                                    2 => route('tenant.config.test.storage',          ['tenantSlug' => $tenant->slug]),
                                    3 => route('tenant.config.test.search',           ['tenantSlug' => $tenant->slug]),
                                ];
                            @endphp
                            <div class="mt-6 pt-5 border-t border-gray-100 space-y-4">

                                {{-- Test connection --}}
                                <div class="flex items-center gap-3">
                                    <button type="button"
                                        onclick="testConnection('{{ $testRoutes[$currentStep] }}')"
                                        id="test-btn"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                                        <svg id="test-icon" class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span id="test-label">Test Connection</span>
                                    </button>
                                    <span id="test-result" class="text-sm hidden"></span>
                                </div>

                                {{-- Back / Next --}}
                                <div class="flex items-center justify-between">
                                    <div>
                                        @if($currentStep > 1)
                                        <a href="{{ route('tenant.config.edit', ['tenantSlug' => $tenant->slug, 'step' => $currentStep - 1]) }}"
                                           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                            Back
                                        </a>
                                        @endif
                                    </div>
                                    <div>
                                        @if($currentStep < $totalSteps)
                                        <button type="submit"
                                            class="inline-flex items-center gap-2 px-5 py-2 bg-[#0f62fe] text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                            Save &amp; Continue
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
                            </div>
                        </form>

                        <p class="mt-4 text-center text-xs text-gray-400">
                            <svg class="w-3 h-3 inline mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            All values are AES-256 encrypted at rest.
                        </p>
                    </div>
                </div>

                {{-- RIGHT — Setup guide + permissions --}}
                <div class="lg:col-span-2 space-y-4">

                    {{-- How to set up --}}
                    @if(!empty($step['setup_steps']))
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <h4 class="text-sm font-bold text-gray-700">How to set this up</h4>
                        </div>
                        <ol class="divide-y divide-gray-50">
                            @foreach($step['setup_steps'] as $i => $instruction)
                            <li class="flex items-start gap-3 px-5 py-3">
                                <span class="w-5 h-5 rounded-full bg-blue-100 text-[#0f62fe] flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5">{{ $i + 1 }}</span>
                                <span class="text-xs text-gray-600 leading-relaxed">{{ $instruction }}</span>
                            </li>
                            @endforeach
                        </ol>
                    </div>
                    @endif

                    {{-- Required permissions --}}
                    @if(!empty($step['permissions']))
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#0f62fe] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <h4 class="text-sm font-bold text-gray-700">Required Graph API Permissions</h4>
                        </div>
                        <div class="divide-y divide-gray-50">
                            @foreach($step['permissions'] as $perm)
                            <div class="px-5 py-3 flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-xs font-mono font-bold text-gray-800">{{ $perm['name'] }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $perm['reason'] }}</p>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 shrink-0 whitespace-nowrap">{{ $perm['type'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>

            </div>

        </div>
    </div>

    <script>
        function toggleVisible(id) {
            const el = document.getElementById(id);
            el.type = el.type === 'password' ? 'text' : 'password';
        }

        async function testConnection(url) {
            const btn    = document.getElementById('test-btn');
            const label  = document.getElementById('test-label');
            const icon   = document.getElementById('test-icon');
            const result = document.getElementById('test-result');

            btn.disabled = true;
            label.textContent = 'Testing…';
            icon.innerHTML = '<circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"/><path d="M4 12a8 8 0 018-8v4" stroke="currentColor" stroke-width="4" stroke-linecap="round" class="opacity-75"/>';
            icon.classList.add('animate-spin');
            result.className = 'text-sm hidden';

            try {
                const resp = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                });
                const data = await resp.json();
                if (data.success) {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                    icon.classList.remove('animate-spin');
                    icon.classList.add('text-green-500');
                    result.textContent = data.message;
                    result.className = 'text-sm text-green-600';
                } else {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                    icon.classList.remove('animate-spin');
                    icon.classList.add('text-red-500');
                    result.textContent = data.message;
                    result.className = 'text-sm text-red-600';
                }
            } catch (e) {
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                icon.classList.remove('animate-spin');
                icon.classList.add('text-red-500');
                result.textContent = 'Request failed — check your network.';
                result.className = 'text-sm text-red-600';
            } finally {
                btn.disabled = false;
                label.textContent = 'Test Connection';
                icon.classList.remove('text-gray-500');
            }
        }
    </script>
</x-app-layout>
