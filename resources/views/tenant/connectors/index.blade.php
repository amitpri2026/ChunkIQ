<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $tenant->name }} — Connectors</h2>
            <a href="{{ route('tenant.dashboard', ['tenantSlug' => $tenant->slug]) }}" class="text-sm text-gray-400 hover:text-gray-600">← Dashboard</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>
            @endif

            <!-- Add connector buttons -->
            <div class="flex flex-wrap gap-2">
                @foreach(\App\Models\Connector::TYPES as $type => $label)
                <a href="{{ route('tenant.connectors.create', ['tenantSlug' => $tenant->slug, 'type' => $type]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-lg hover:border-blue-400 hover:text-blue-600 transition-colors">
                    + {{ $label }}
                </a>
                @endforeach
            </div>

            @if($connectors->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-10 text-center">
                <p class="text-gray-400 text-sm">No connectors yet. Add one above to start ingesting data.</p>
            </div>
            @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 divide-y divide-gray-100">
                @foreach($connectors as $connector)
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-lg">
                            @switch($connector->type)
                                @case('sharepoint') 📁 @break
                                @case('teams')      💬 @break
                                @case('onedrive')   ☁️ @break
                                @case('onenote')    📓 @break
                            @endswitch
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">{{ $connector->name }}</p>
                            <p class="text-xs text-gray-400">{{ $connector->getTypeLabel() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-semibold px-2 py-1 rounded-full
                            {{ $connector->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ ucfirst($connector->status) }}
                        </span>
                        <a href="{{ route('tenant.connectors.edit', ['tenantSlug' => $tenant->slug, 'connector' => $connector->id]) }}"
                           class="text-xs text-blue-600 hover:text-blue-800 font-semibold">Edit</a>
                        <form method="POST" action="{{ route('tenant.connectors.destroy', ['tenantSlug' => $tenant->slug, 'connector' => $connector->id]) }}"
                              onsubmit="return confirm('Delete connector {{ $connector->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-semibold">Delete</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
