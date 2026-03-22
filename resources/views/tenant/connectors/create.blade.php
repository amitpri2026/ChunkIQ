<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Connector</h2>
            <a href="{{ route('tenant.connectors.index', ['tenantSlug' => $tenant->slug]) }}" class="text-sm text-gray-400 hover:text-gray-600">← Connectors</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">

                <!-- Type selector -->
                <div class="flex flex-wrap gap-2 mb-6">
                    @foreach(\App\Models\Connector::TYPES as $t => $label)
                    <a href="{{ route('tenant.connectors.create', ['tenantSlug' => $tenant->slug, 'type' => $t]) }}"
                       class="px-3 py-1.5 rounded-lg text-sm font-semibold border transition-colors
                           {{ $type === $t ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-200 hover:border-blue-300' }}">
                        {{ $label }}
                    </a>
                    @endforeach
                </div>

                <form method="POST" action="{{ route('tenant.connectors.store', ['tenantSlug' => $tenant->slug]) }}" class="space-y-5">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">

                    <div>
                        <x-input-label for="name" value="Display Name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                            placeholder="e.g. HR SharePoint Site"
                            value="{{ old('name') }}" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    @foreach(\App\Models\Connector::TYPE_FIELDS[$type] as $field => $meta)
                    <div>
                        <x-input-label for="{{ $field }}" value="{{ $meta['label'] }}" />
                        <x-text-input id="{{ $field }}" name="{{ $field }}" type="text" class="mt-1 block w-full"
                            placeholder="{{ $meta['placeholder'] }}"
                            value="{{ old($field) }}" />
                        <x-input-error :messages="$errors->get($field)" class="mt-1" />
                    </div>
                    @endforeach

                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('tenant.connectors.index', ['tenantSlug' => $tenant->slug]) }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                        <x-primary-button>Add Connector</x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
