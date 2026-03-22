<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-gray-900 leading-tight">Connectors</h2>
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

    @php
    $connectorMeta = [
        'sharepoint' => [
            'label'       => 'SharePoint',
            'icon_path'   => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z',
            'color'       => 'blue',
            'tagline'     => 'Index document libraries, site pages, and lists from any SharePoint site.',
            'features'    => ['Document libraries & files', 'Site pages & wikis', 'SharePoint lists', 'Versioned content'],
            'setup'       => [
                'Ensure your App Registration has Sites.Read.All and Files.Read.All Graph API permissions.',
                'Navigate to your SharePoint site and copy the URL from the address bar (e.g. https://contoso.sharepoint.com/sites/HR).',
                'Enter the document library name — usually "Documents". Leave blank to use the default library.',
                'ChunkIQ will crawl and index all files in the library on each pipeline run.',
                'Run the pipeline from Pipeline Jobs to trigger the first ingestion.',
            ],
        ],
        'teams' => [
            'label'       => 'Microsoft Teams',
            'icon_path'   => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
            'color'       => 'purple',
            'tagline'     => 'Ingest Teams channel messages and files shared within a team.',
            'features'    => ['Channel messages & threads', 'Shared files & attachments', 'Meeting notes & recordings index', 'Multi-channel support'],
            'setup'       => [
                'Ensure your App Registration has Team.ReadBasic.All and ChannelMessage.Read.All permissions.',
                'Find the SharePoint site URL for the Team (each Team has a backing SharePoint site).',
                'Optionally provide the Team ID and Channel ID to limit ingestion scope.',
                'Leave Team ID and Channel ID blank to ingest all channels across the team.',
                'Run the pipeline from Pipeline Jobs once the connector is saved.',
            ],
        ],
        'onedrive' => [
            'label'       => 'OneDrive',
            'icon_path'   => 'M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z',
            'color'       => 'sky',
            'tagline'     => 'Connect to a user\'s OneDrive to index personal or shared files and folders.',
            'features'    => ['Personal file libraries', 'Shared folders & drives', 'Selective folder paths', 'Any file type support'],
            'setup'       => [
                'Ensure your App Registration has Files.Read.All and User.Read.All Graph API permissions.',
                'Enter the email address of the user whose OneDrive you want to index.',
                'Optionally specify a folder path (e.g. /Documents/Reports). Leave blank to index from the root.',
                'ChunkIQ will enumerate all files under the specified path recursively.',
                'Multiple OneDrive connectors can be created for different users or folder scopes.',
            ],
        ],
        'onenote' => [
            'label'       => 'OneNote',
            'icon_path'   => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
            'color'       => 'violet',
            'tagline'     => 'Index OneNote notebooks, sections, and pages from SharePoint-backed sites.',
            'features'    => ['Notebooks & sections', 'Individual pages & content', 'Embedded images (OCR-ready)', 'Section-level filtering'],
            'setup'       => [
                'Ensure your App Registration has Notes.Read.All Graph API permission.',
                'Enter the SharePoint site URL where the OneNote notebook is stored.',
                'Optionally enter the Notebook name or ID to limit to a specific notebook.',
                'Use the Section Filter to narrow ingestion to specific sections by name.',
                'Leave Notebook ID and Section Filter blank to ingest all notebooks on the site.',
            ],
        ],
    ];

    $typeColors = [
        'blue'   => ['bg' => 'bg-blue-100',   'text' => 'text-blue-600',   'border' => 'border-blue-200',   'badge' => 'bg-blue-100 text-blue-700'],
        'purple' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600', 'border' => 'border-purple-200', 'badge' => 'bg-purple-100 text-purple-700'],
        'sky'    => ['bg' => 'bg-sky-100',    'text' => 'text-sky-600',    'border' => 'border-sky-200',    'badge' => 'bg-sky-100 text-sky-700'],
        'violet' => ['bg' => 'bg-violet-100', 'text' => 'text-violet-600', 'border' => 'border-violet-200', 'badge' => 'bg-violet-100 text-violet-700'],
    ];

    $grouped = $connectors->groupBy('type');
    @endphp

    <div x-data="{ activeType: 'all' }" class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-5 bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex gap-6 items-start">

                {{-- LEFT SIDEBAR --}}
                <div class="w-56 shrink-0 space-y-2">

                    {{-- All connectors --}}
                    <button @click="activeType = 'all'"
                            :class="activeType === 'all' ? 'bg-[#0f62fe] text-white shadow-sm' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'"
                            class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg text-sm font-semibold transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                            All Connectors
                        </span>
                        <span :class="activeType === 'all' ? 'bg-white/25 text-white' : 'bg-gray-100 text-gray-600'"
                              class="text-[10px] font-bold px-1.5 py-0.5 rounded-full">
                            {{ $connectors->count() }}
                        </span>
                    </button>

                    <div class="pt-1 pb-1">
                        <p class="px-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">By Type</p>
                        @foreach($connectorMeta as $type => $meta)
                        @php $c = $typeColors[$meta['color']]; $cnt = $grouped->get($type, collect())->count(); @endphp
                        <button @click="activeType = '{{ $type }}'"
                                :class="activeType === '{{ $type }}' ? 'bg-[#0f62fe] text-white shadow-sm' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'"
                                class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg text-sm font-medium transition-colors mb-1">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $meta['icon_path'] }}"/>
                                </svg>
                                {{ $meta['label'] }}
                            </span>
                            <span :class="activeType === '{{ $type }}' ? 'bg-white/25 text-white' : 'bg-gray-100 text-gray-600'"
                                  class="text-[10px] font-bold px-1.5 py-0.5 rounded-full">
                                {{ $cnt }}
                            </span>
                        </button>
                        @endforeach
                    </div>

                    <div class="pt-2 border-t border-gray-200">
                        <p class="px-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Add New</p>
                        @foreach($connectorMeta as $type => $meta)
                        @php $c = $typeColors[$meta['color']]; @endphp
                        <a href="{{ route('tenant.connectors.create', ['tenantSlug' => $tenant->slug, 'type' => $type]) }}"
                           class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm text-gray-600 hover:bg-blue-50 hover:text-[#0f62fe] transition-colors mb-0.5">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ $meta['label'] }}
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- MAIN CONTENT --}}
                <div class="flex-1 min-w-0 space-y-5">

                    {{-- Connector list --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

                        {{-- Header bar --}}
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-gray-800"
                                    x-text="activeType === 'all' ? 'All Connectors ({{ $connectors->count() }})' :
                                        @foreach($connectorMeta as $type => $meta) activeType === '{{ $type }}' ? '{{ $meta['label'] }} (' + {{ $grouped->get($type, collect())->count() }} + ')' : @endforeach ''">
                                </h3>
                                <p class="text-xs text-gray-400 mt-0.5">Manage your data source connections</p>
                            </div>
                        </div>

                        @if($connectors->isEmpty())
                        <div class="py-16 text-center">
                            <div class="w-14 h-14 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-[#0f62fe]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-gray-700">No connectors yet</p>
                            <p class="text-xs text-gray-400 mt-1">Add a connector from the sidebar to start ingesting data.</p>
                        </div>
                        @else
                        <div class="divide-y divide-gray-100">
                            @foreach($connectors as $connector)
                            @php
                                $meta = $connectorMeta[$connector->type] ?? null;
                                $c    = $meta ? $typeColors[$meta['color']] : $typeColors['blue'];
                            @endphp
                            <div x-show="activeType === 'all' || activeType === '{{ $connector->type }}'"
                                 class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition-colors">
                                <div class="w-10 h-10 rounded-lg {{ $c['bg'] }} flex items-center justify-center shrink-0">
                                    @if($meta)
                                    <svg class="w-5 h-5 {{ $c['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $meta['icon_path'] }}"/>
                                    </svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-800 text-sm">{{ $connector->name }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $connector->getTypeLabel() }}</p>
                                </div>
                                <div class="flex items-center gap-3 shrink-0">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full
                                        {{ $connector->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ ucfirst($connector->status) }}
                                    </span>
                                    <a href="{{ route('tenant.connectors.edit', ['tenantSlug' => $tenant->slug, 'connector' => $connector->id]) }}"
                                       class="text-xs font-semibold text-[#0f62fe] hover:text-blue-800">Edit</a>
                                    <form method="POST"
                                          action="{{ route('tenant.connectors.destroy', ['tenantSlug' => $tenant->slug, 'connector' => $connector->id]) }}"
                                          onsubmit="return confirm('Delete {{ $connector->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-700">Delete</button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    {{-- Per-type setup guide (shown when a specific type is selected) --}}
                    @foreach($connectorMeta as $type => $meta)
                    @php $c = $typeColors[$meta['color']]; @endphp
                    <div x-show="activeType === '{{ $type }}'"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden"
                         style="display:none;">

                        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg {{ $c['bg'] }} flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 {{ $c['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $meta['icon_path'] }}"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-800">{{ $meta['label'] }} — About & Setup</h3>
                                <p class="text-xs text-gray-500">{{ $meta['tagline'] }}</p>
                            </div>
                            <a href="{{ route('tenant.connectors.create', ['tenantSlug' => $tenant->slug, 'type' => $type]) }}"
                               class="ml-auto inline-flex items-center gap-1.5 px-4 py-2 bg-[#0f62fe] text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                Add {{ $meta['label'] }}
                            </a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-0 divide-y md:divide-y-0 md:divide-x divide-gray-100">

                            {{-- Features --}}
                            <div class="px-6 py-5">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">What gets indexed</p>
                                <ul class="space-y-2">
                                    @foreach($meta['features'] as $feature)
                                    <li class="flex items-center gap-2.5 text-sm text-gray-700">
                                        <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ $feature }}
                                    </li>
                                    @endforeach
                                </ul>
                            </div>

                            {{-- Setup steps --}}
                            <div class="px-6 py-5">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">How to set up</p>
                                <ol class="space-y-3">
                                    @foreach($meta['setup'] as $i => $step)
                                    <li class="flex items-start gap-3">
                                        <span class="w-5 h-5 rounded-full bg-blue-100 text-[#0f62fe] flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5">{{ $i + 1 }}</span>
                                        <span class="text-xs text-gray-600 leading-relaxed">{{ $step }}</span>
                                    </li>
                                    @endforeach
                                </ol>
                            </div>

                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
