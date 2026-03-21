<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome banner -->
            <div class="bg-gradient-to-r from-blue-700 to-blue-500 rounded-xl p-6 text-white shadow">
                <p class="text-sm font-semibold uppercase tracking-widest text-blue-200 mb-1">Welcome back</p>
                <h1 class="text-2xl font-bold">{{ Auth::user()->name }}</h1>
                <p class="text-blue-200 mt-1 text-sm">ChunkIQ Portal — unstructured data pipeline ready.</p>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Chunks Indexed</p>
                    <p class="text-3xl font-extrabold text-gray-800">916</p>
                    <p class="text-xs text-gray-400 mt-1">across 58 files</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Active Sources</p>
                    <p class="text-3xl font-extrabold text-gray-800">4</p>
                    <p class="text-xs text-gray-400 mt-1">SharePoint · Teams · OneNote · OneDrive</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Vector Dimensions</p>
                    <p class="text-3xl font-extrabold text-gray-800">1,536</p>
                    <p class="text-xs text-gray-400 mt-1">text-embedding-3-small</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Index Status</p>
                    <p class="text-3xl font-extrabold text-green-600">Live</p>
                    <p class="text-xs text-gray-400 mt-1">Azure AI Search</p>
                </div>
            </div>

            <!-- Source platforms -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="font-bold text-gray-700 mb-4">Data Sources</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    @foreach([
                        ['icon' => '📁', 'name' => 'SharePoint',       'desc' => 'Document libraries across all sites',        'status' => 'active',   'color' => 'green'],
                        ['icon' => '💬', 'name' => 'Microsoft Teams',   'desc' => 'Channel files & team site libraries',        'status' => 'active',   'color' => 'green'],
                        ['icon' => '📓', 'name' => 'OneNote',           'desc' => 'Notebooks, sections & page HTML',            'status' => 'active',   'color' => 'green'],
                        ['icon' => '☁️', 'name' => 'OneDrive',          'desc' => 'Personal & shared drives',                   'status' => 'active',   'color' => 'green'],
                    ] as $src)
                    <div class="flex items-start gap-3 p-4 rounded-lg border border-gray-100 bg-gray-50 hover:border-blue-200 hover:bg-blue-50 transition-colors">
                        <span class="text-2xl mt-0.5">{{ $src['icon'] }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 text-sm">{{ $src['name'] }}</p>
                            <p class="text-xs text-gray-400 mt-0.5 leading-snug">{{ $src['desc'] }}</p>
                        </div>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-green-100 text-green-700 shrink-0">Active</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Pipeline stages -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="font-bold text-gray-700 mb-4">Pipeline Stages</h2>
                <div class="space-y-3">
                    @foreach([
                        ['stage' => 'Ingestion',           'desc' => 'Microsoft 365 → Azure Storage (SharePoint, Teams, OneNote, OneDrive)', 'icon' => '📥'],
                        ['stage' => 'Document Extraction', 'desc' => 'Native format extractors for all supported file types',                   'icon' => '📄'],
                        ['stage' => 'Chunking & Embedding','desc' => 'Hybrid semantic chunking + Azure OpenAI 1,536-dim embeddings',           'icon' => '✂️'],
                        ['stage' => 'Indexing',            'desc' => 'Azure AI Search — hybrid BM25 + vector + semantic re-ranking',           'icon' => '⚡'],
                    ] as $item)
                    <div class="flex items-center gap-4 p-3 rounded-lg bg-gray-50">
                        <span class="text-xl">{{ $item['icon'] }}</span>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-700 text-sm">{{ $item['stage'] }}</p>
                            <p class="text-xs text-gray-400">{{ $item['desc'] }}</p>
                        </div>
                        <span class="text-xs font-semibold px-2 py-1 rounded-full bg-green-100 text-green-700">Complete</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Supported file types -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="font-bold text-gray-700 mb-4">Supported File Types</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach([
                        ['ext' => '.pdf',   'lib' => 'Native extractor'],
                        ['ext' => '.docx',  'lib' => 'Native extractor'],
                        ['ext' => '.xlsx',  'lib' => 'Native extractor'],
                        ['ext' => '.pptx',  'lib' => 'Native extractor'],
                        ['ext' => '.xlsm',  'lib' => 'Native extractor'],
                        ['ext' => '.xls',   'lib' => 'Native extractor'],
                        ['ext' => '.html',  'lib' => 'Native extractor'],
                        ['ext' => '.csv',   'lib' => 'csv'],
                        ['ext' => '.json',  'lib' => 'json'],
                        ['ext' => '.txt',   'lib' => 'utf-8'],
                        ['ext' => '.md',    'lib' => 'utf-8'],
                    ] as $ft)
                    <div class="flex items-center gap-1.5 bg-blue-50 border border-blue-100 rounded-lg px-3 py-1.5">
                        <span class="font-mono font-bold text-blue-700 text-xs">{{ $ft['ext'] }}</span>
                        <span class="text-gray-300 text-xs">·</span>
                        <span class="text-gray-400 text-xs">{{ $ft['lib'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Quick links -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="font-bold text-gray-700 mb-4">Quick Links</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <a href="https://srch-sp-proc-001.search.windows.net" target="_blank"
                       class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-blue-400 hover:bg-blue-50 transition-colors">
                        <span class="text-xl">🔎</span>
                        <div>
                            <p class="font-semibold text-sm text-gray-700">Azure AI Search</p>
                            <p class="text-xs text-gray-400">srch-sp-proc-001</p>
                        </div>
                    </a>
                    <a href="https://portal.azure.com" target="_blank"
                       class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-blue-400 hover:bg-blue-50 transition-colors">
                        <span class="text-xl">☁️</span>
                        <div>
                            <p class="font-semibold text-sm text-gray-700">Azure Portal</p>
                            <p class="text-xs text-gray-400">Manage resources</p>
                        </div>
                    </a>
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-blue-400 hover:bg-blue-50 transition-colors">
                        <span class="text-xl">👤</span>
                        <div>
                            <p class="font-semibold text-sm text-gray-700">My Profile</p>
                            <p class="text-xs text-gray-400">Update settings</p>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
