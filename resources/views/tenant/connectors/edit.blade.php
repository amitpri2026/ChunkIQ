<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Connector — {{ $connector->name }}</h2>
            <a href="{{ route('tenant.connectors.index', ['tenantSlug' => $tenant->slug]) }}" class="text-sm text-gray-400 hover:text-gray-600">← Connectors</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">

                <p class="text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-full inline-block mb-5">
                    {{ $connector->getTypeLabel() }}
                </p>

                <form method="POST" action="{{ route('tenant.connectors.update', ['tenantSlug' => $tenant->slug, 'connector' => $connector->id]) }}"
                      class="space-y-5">
                    @csrf @method('PATCH')

                    <div>
                        <x-input-label for="name" value="Display Name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                            value="{{ old('name', $connector->name) }}" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    @foreach(\App\Models\Connector::TYPE_FIELDS[$connector->type] as $field => $meta)
                    <div>
                        <x-input-label for="{{ $field }}" value="{{ $meta['label'] }}" />
                        <x-text-input id="{{ $field }}" name="{{ $field }}" type="text" class="mt-1 block w-full"
                            placeholder="{{ $meta['placeholder'] }}"
                            value="{{ old($field, $settings[$field] ?? '') }}" />
                        <x-input-error :messages="$errors->get($field)" class="mt-1" />
                    </div>
                    @endforeach

                    <div>
                        <x-input-label for="status" value="Status" />
                        <select id="status" name="status"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="active"   {{ $connector->status === 'active'   ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $connector->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('tenant.connectors.index', ['tenantSlug' => $tenant->slug]) }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                        <x-primary-button>Save Changes</x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
