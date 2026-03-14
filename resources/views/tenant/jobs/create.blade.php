<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Job</h2>
            <a href="{{ route('tenant.jobs.index', ['tenantSlug' => $tenant->slug]) }}" class="text-sm text-gray-400 hover:text-gray-600">← Jobs</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">

                <form method="POST" action="{{ route('tenant.jobs.store', ['tenantSlug' => $tenant->slug]) }}" class="space-y-5">
                    @csrf

                    <div>
                        <x-input-label for="name" value="Job Name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                            placeholder="e.g. Ingest HR SharePoint Weekly"
                            value="{{ old('name') }}" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="type" value="Job Type" />
                        <select id="type" name="type" onchange="toggleConnector(this.value)"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                            @foreach(\App\Models\PipelineJob::TYPES as $val => $label)
                            <option value="{{ $val }}" {{ old('type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-1" />
                    </div>

                    <div id="connector-section">
                        <x-input-label for="connector_id" value="Source Connector" />
                        <select id="connector_id" name="connector_id"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="">— None / use ADLS directly —</option>
                            @foreach($connectors as $connector)
                            <option value="{{ $connector->id }}" {{ old('connector_id') == $connector->id ? 'selected' : '' }}>
                                {{ $connector->name }} ({{ $connector->getTypeLabel() }})
                            </option>
                            @endforeach
                        </select>
                        @if($connectors->isEmpty())
                            <p class="text-xs text-amber-600 mt-1">
                                No active connectors yet.
                                <a href="{{ route('tenant.connectors.create', ['tenantSlug' => $tenant->slug]) }}" class="underline">Add one first.</a>
                            </p>
                        @endif
                        <x-input-error :messages="$errors->get('connector_id')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="notes" value="Notes (optional)" />
                        <textarea id="notes" name="notes" rows="2"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm"
                            placeholder="Any notes about this job">{{ old('notes') }}</textarea>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('tenant.jobs.index', ['tenantSlug' => $tenant->slug]) }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                        <x-primary-button>Create Job</x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        function toggleConnector(type) {
            document.getElementById('connector-section').style.display =
                type === 'processing' ? 'none' : 'block';
        }
        toggleConnector(document.getElementById('type').value);
    </script>
</x-app-layout>
