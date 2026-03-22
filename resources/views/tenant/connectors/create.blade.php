<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-gray-900 leading-tight">Add Connector</h2>
                    <p class="text-xs text-gray-500">{{ $tenant->name }}</p>
                </div>
            </div>
            <a href="{{ route('tenant.connectors.index', ['tenantSlug' => $tenant->slug]) }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Connectors
            </a>
        </div>
    </x-slot>

    @php
    $connectorMeta = [
        'sharepoint' => [
            'label'     => 'SharePoint',
            'icon_path' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z',
            'color'     => 'blue',
            'tagline'   => 'Index document libraries, site pages, and lists from any SharePoint site.',
            'features'  => ['Document libraries & files', 'Site pages & wikis', 'SharePoint lists', 'Versioned content'],
            'setup'     => [
                'Ensure your App Registration has Sites.Read.All and Files.Read.All Graph API permissions.',
                'Navigate to your SharePoint site and copy the URL from the address bar.',
                'Enter the document library name — usually "Documents". Leave blank for the default.',
                'ChunkIQ will crawl and index all files in the library on each pipeline run.',
            ],
        ],
        'teams' => [
            'label'     => 'Microsoft Teams',
            'icon_path' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
            'color'     => 'purple',
            'tagline'   => 'Ingest Teams channel messages and files shared within a team.',
            'features'  => ['Channel messages & threads', 'Shared files & attachments', 'Meeting notes index', 'Multi-channel support'],
            'setup'     => [
                'Ensure your App Registration has Team.ReadBasic.All and ChannelMessage.Read.All permissions.',
                'Find the SharePoint site URL for the Team (each Team has a backing SharePoint site).',
                'Optionally provide Team ID and Channel ID to limit ingestion scope.',
                'Leave Team ID and Channel ID blank to ingest all channels across the team.',
            ],
        ],
        'onedrive' => [
            'label'     => 'OneDrive',
            'icon_path' => 'M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z',
            'color'     => 'sky',
            'tagline'   => 'Connect to a user\'s OneDrive to index personal or shared files.',
            'features'  => ['Personal file libraries', 'Shared folders & drives', 'Selective folder paths', 'Any file type support'],
            'setup'     => [
                'Ensure your App Registration has Files.Read.All and User.Read.All Graph API permissions.',
                'Enter the email address of the user whose OneDrive you want to index.',
                'Optionally specify a folder path (e.g. /Documents/Reports). Leave blank for root.',
                'Multiple OneDrive connectors can target different users or folder scopes.',
            ],
        ],
        'onenote' => [
            'label'     => 'OneNote',
            'icon_path' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
            'color'     => 'violet',
            'tagline'   => 'Index OneNote notebooks, sections, and pages from SharePoint-backed sites.',
            'features'  => ['Notebooks & sections', 'Individual pages & content', 'Embedded images (OCR-ready)', 'Section-level filtering'],
            'setup'     => [
                'Ensure your App Registration has Notes.Read.All Graph API permission.',
                'Enter the SharePoint site URL where the OneNote notebook is stored.',
                'Optionally enter the Notebook name or ID to limit to a specific notebook.',
                'Leave Notebook ID and Section Filter blank to ingest all notebooks on the site.',
            ],
        ],
    ];
    $typeColors = [
        'blue'   => ['bg' => 'bg-blue-100',   'text' => 'text-blue-600'],
        'purple' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600'],
        'sky'    => ['bg' => 'bg-sky-100',    'text' => 'text-sky-600'],
        'violet' => ['bg' => 'bg-violet-100', 'text' => 'text-violet-600'],
    ];
    $activeMeta   = $connectorMeta[$type];
    $activeColors = $typeColors[$activeMeta['color']];
    $typeFields   = \App\Models\Connector::TYPE_FIELDS[$type];
    $fileTypes    = \App\Models\Connector::FILE_TYPES;
    $defaultTypes = \App\Models\Connector::DEFAULT_FILE_TYPES;
    @endphp

    <div x-data="{
            testStatus: null,
            testLoading: false,
            async runTest() {
                this.testLoading = true;
                this.testStatus  = null;
                const form     = document.getElementById('connector-form');
                const formData = new FormData(form);
                const settings = {};
                @foreach($typeFields as $field => $meta)
                settings['{{ $field }}'] = formData.get('{{ $field }}') ?? '';
                @endforeach
                try {
                    const res = await fetch('{{ route('tenant.connectors.test', ['tenantSlug' => $tenant->slug]) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ type: '{{ $type }}', settings }),
                    });
                    const data = await res.json();
                    this.testStatus = data;
                } catch (e) {
                    this.testStatus = { success: false, message: 'Network error: ' + e.message };
                } finally {
                    this.testLoading = false;
                }
            }
        }" class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex gap-6 items-start">

                {{-- LEFT SIDEBAR --}}
                <div class="w-56 shrink-0 space-y-2">

                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Connector Type</p>
                        </div>
                        <div class="p-2 space-y-1">
                            @foreach($connectorMeta as $t => $meta)
                            @php $c = $typeColors[$meta['color']]; @endphp
                            <a href="{{ route('tenant.connectors.create', ['tenantSlug' => $tenant->slug, 'type' => $t]) }}"
                               class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                                   {{ $type === $t ? 'bg-[#0f62fe] text-white shadow-sm' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4 shrink-0 {{ $type === $t ? 'text-white' : $c['text'] }}"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $meta['icon_path'] }}"/>
                                </svg>
                                {{ $meta['label'] }}
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Actions</p>
                        </div>
                        <div class="p-2">
                            <a href="{{ route('tenant.connectors.index', ['tenantSlug' => $tenant->slug]) }}"
                               class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                </svg>
                                All Connectors
                            </a>
                        </div>
                    </div>
                </div>

                {{-- MAIN CONTENT --}}
                <div class="flex-1 min-w-0">
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">

                        {{-- FORM PANEL (3/5) --}}
                        <div class="lg:col-span-3 space-y-5">

                            {{-- Type banner --}}
                            <div class="bg-[#001141] rounded-xl px-6 py-4 flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg {{ $activeColors['bg'] }} flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 {{ $activeColors['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $activeMeta['icon_path'] }}"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white font-semibold text-sm">{{ $activeMeta['label'] }} Connector</p>
                                    <p class="text-blue-200 text-xs mt-0.5">{{ $activeMeta['tagline'] }}</p>
                                </div>
                            </div>

                            <form id="connector-form" method="POST"
                                  action="{{ route('tenant.connectors.store', ['tenantSlug' => $tenant->slug]) }}"
                                  class="space-y-5">
                                @csrf
                                <input type="hidden" name="type" value="{{ $type }}">

                                {{-- SECTION 1: Display Name --}}
                                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                                    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                                        <p class="text-xs font-bold text-gray-600 uppercase tracking-widest">Display Name</p>
                                        <p class="text-xs text-gray-400 mt-0.5">A friendly label for this connector shown across the portal.</p>
                                    </div>
                                    <div class="px-5 py-5">
                                        <x-text-input id="name" name="name" type="text" class="block w-full"
                                            placeholder="e.g. HR SharePoint Site"
                                            value="{{ old('name') }}" required autofocus />
                                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                                    </div>
                                </div>

                                {{-- SECTION 2: Connection Details --}}
                                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                                    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                                        <p class="text-xs font-bold text-gray-600 uppercase tracking-widest">Connection Details</p>
                                        <p class="text-xs text-gray-400 mt-0.5">Specify where ChunkIQ should connect for this {{ $activeMeta['label'] }} connector.</p>
                                    </div>
                                    <div class="px-5 py-5 space-y-5">
                                        @foreach($typeFields as $field => $meta)
                                        <div>
                                            <label for="{{ $field }}" class="block text-sm font-semibold text-gray-700 mb-1">{{ $meta['label'] }}</label>
                                            <x-text-input id="{{ $field }}" name="{{ $field }}" type="text" class="block w-full"
                                                placeholder="{{ $meta['placeholder'] }}"
                                                value="{{ old($field) }}" />
                                            @if(!empty($meta['hint']))
                                            <p class="mt-1 text-xs text-gray-400">{{ $meta['hint'] }}</p>
                                            @endif
                                            <x-input-error :messages="$errors->get($field)" class="mt-1" />
                                        </div>
                                        @endforeach
                                    </div>

                                    {{-- Test Connector --}}
                                    <div class="px-5 pb-5">
                                        <div class="border-t border-gray-100 pt-4 flex items-center gap-3">
                                            <button type="button" @click="runTest()"
                                                    :disabled="testLoading"
                                                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 text-white text-sm font-semibold rounded-lg hover:bg-gray-900 disabled:opacity-60 transition-colors">
                                                <svg x-show="!testLoading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <svg x-show="testLoading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                                                </svg>
                                                <span x-text="testLoading ? 'Testing…' : 'Test Connector'"></span>
                                            </button>
                                            <div x-show="testStatus !== null" x-transition class="flex items-center gap-2">
                                                <span :class="testStatus?.success ? 'text-green-600' : 'text-red-600'"
                                                      class="text-xs font-semibold flex items-center gap-1.5">
                                                    <svg x-show="testStatus?.success" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    <svg x-show="!testStatus?.success" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    <span x-text="testStatus?.message"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- SECTION 3: File Types --}}
                                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                                    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                                        <p class="text-xs font-bold text-gray-600 uppercase tracking-widest">Supported File Types</p>
                                        <p class="text-xs text-gray-400 mt-0.5">Choose which file formats ChunkIQ will process during ingestion.</p>
                                    </div>
                                    <div class="px-5 py-5">
                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                            @foreach($fileTypes as $key => $ft)
                                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                                <input type="checkbox" name="file_types[]" value="{{ $key }}"
                                                    {{ in_array($key, $defaultTypes) ? 'checked' : '' }}
                                                    class="w-4 h-4 rounded border-gray-300 text-[#0f62fe] focus:ring-[#0f62fe]">
                                                <span class="text-sm text-gray-700 group-hover:text-gray-900">
                                                    {{ $ft['label'] }}
                                                    <span class="text-xs text-gray-400">{{ $ft['ext'] }}</span>
                                                </span>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center justify-between">
                                    <a href="{{ route('tenant.connectors.index', ['tenantSlug' => $tenant->slug]) }}"
                                       class="text-sm text-gray-500 hover:text-gray-700 font-medium">Cancel</a>
                                    <button type="submit"
                                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0f62fe] text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                        Add Connector
                                    </button>
                                </div>

                            </form>
                        </div>

                        {{-- SETUP GUIDE (2/5) --}}
                        <div class="lg:col-span-2 space-y-5">
                            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden sticky top-24">
                                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                                    <div class="w-7 h-7 rounded-lg {{ $activeColors['bg'] }} flex items-center justify-center shrink-0">
                                        <svg class="w-3.5 h-3.5 {{ $activeColors['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $activeMeta['icon_path'] }}"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-800">{{ $activeMeta['label'] }} Setup Guide</p>
                                </div>

                                {{-- What gets indexed --}}
                                <div class="px-5 py-4 border-b border-gray-100">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">What gets indexed</p>
                                    <ul class="space-y-2">
                                        @foreach($activeMeta['features'] as $feature)
                                        <li class="flex items-center gap-2 text-sm text-gray-700">
                                            <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            {{ $feature }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>

                                {{-- Setup steps --}}
                                <div class="px-5 py-4">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">How to set up</p>
                                    <ol class="space-y-3">
                                        @foreach($activeMeta['setup'] as $i => $step)
                                        <li class="flex items-start gap-3">
                                            <span class="w-5 h-5 rounded-full bg-blue-100 text-[#0f62fe] flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5">{{ $i + 1 }}</span>
                                            <span class="text-xs text-gray-600 leading-relaxed">{{ $step }}</span>
                                        </li>
                                        @endforeach
                                    </ol>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
