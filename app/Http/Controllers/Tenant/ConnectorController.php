<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Connector;
use App\Support\TenantManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConnectorController extends Controller
{
    public function __construct(private TenantManager $manager) {}

    public function index(): View
    {
        $tenant     = $this->manager->get();
        $connectors = $tenant->connectors()->latest()->get();

        return view('tenant.connectors.index', compact('tenant', 'connectors'));
    }

    public function create(Request $request): View
    {
        $tenant = $this->manager->get();
        $type   = $request->query('type', 'sharepoint');

        if (!array_key_exists($type, Connector::TYPES)) {
            $type = 'sharepoint';
        }

        return view('tenant.connectors.create', compact('tenant', 'type'));
    }

    public function store(Request $request): RedirectResponse
    {
        $type = $request->input('type');

        if (!array_key_exists($type, Connector::TYPES)) {
            abort(422, 'Invalid connector type.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', 'in:' . implode(',', array_keys(Connector::TYPES))],
        ]);

        $fields   = Connector::TYPE_FIELDS[$type] ?? [];
        $settings = [];
        foreach (array_keys($fields) as $field) {
            $settings[$field] = $request->input($field, '');
        }

        $tenant = $this->manager->get();
        $tenant->connectors()->create([
            'type'     => $type,
            'name'     => $request->name,
            'settings' => $settings,
            'status'   => 'active',
        ]);

        return redirect()->route('tenant.connectors.index', ['tenantSlug' => $tenant->slug])
            ->with('success', 'Connector "' . $request->name . '" added.');
    }

    public function edit(Connector $connector): View
    {
        $tenant = $this->manager->get();
        abort_if($connector->tenant_id !== $tenant->id, 403);

        $settings = $connector->settings_decrypted;

        return view('tenant.connectors.edit', compact('tenant', 'connector', 'settings'));
    }

    public function update(Request $request, Connector $connector): RedirectResponse
    {
        $tenant = $this->manager->get();
        abort_if($connector->tenant_id !== $tenant->id, 403);

        $request->validate(['name' => ['required', 'string', 'max:100']]);

        $fields   = Connector::TYPE_FIELDS[$connector->type] ?? [];
        $settings = [];
        foreach (array_keys($fields) as $field) {
            $settings[$field] = $request->input($field, '');
        }

        $connector->update([
            'name'     => $request->name,
            'settings' => $settings,
            'status'   => $request->input('status', 'active'),
        ]);

        return redirect()->route('tenant.connectors.index', ['tenantSlug' => $tenant->slug])
            ->with('success', 'Connector updated.');
    }

    public function destroy(Connector $connector): RedirectResponse
    {
        $tenant = $this->manager->get();
        abort_if($connector->tenant_id !== $tenant->id, 403);

        $connector->delete();

        return redirect()->route('tenant.connectors.index', ['tenantSlug' => $tenant->slug])
            ->with('success', 'Connector removed.');
    }
}
