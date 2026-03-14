<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Support\TenantManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TenantConfigController extends Controller
{
    // Keys exposed in the settings form — maps to the equivalent .env keys in ChunkIQ_Cloud
    public const CONFIG_KEYS = [
        // Azure identity
        'azure_tenant_id'        => 'Azure Tenant ID',
        'azure_client_id'        => 'Azure Client ID (App Registration)',
        'azure_client_secret'    => 'Azure Client Secret',
        'azure_subscription_id'  => 'Azure Subscription ID',
        // ADLS
        'adls_account_name'      => 'ADLS Gen2 Account Name',
        'adls_container'         => 'ADLS Container Name',
        'adls_key'               => 'ADLS Access Key',
        // Function App URLs & keys (one per connector type + processor)
        'fa_sharepoint_url'      => 'SharePoint Function App URL',
        'fa_sharepoint_key'      => 'SharePoint Function App Key',
        'fa_teams_url'           => 'Teams Function App URL',
        'fa_teams_key'           => 'Teams Function App Key',
        'fa_onedrive_url'        => 'OneDrive Function App URL',
        'fa_onedrive_key'        => 'OneDrive Function App Key',
        'fa_onenote_url'         => 'OneNote Function App URL',
        'fa_onenote_key'         => 'OneNote Function App Key',
        'fa_processor_url'       => 'Processor Function App URL',
        'fa_processor_key'       => 'Processor Function App Key',
    ];

    public function __construct(private TenantManager $manager) {}

    public function edit(): View
    {
        $tenant = $this->manager->get();

        // Load existing values (decrypted) for display — mask secrets
        $configs = [];
        foreach (self::CONFIG_KEYS as $key => $label) {
            $value = $tenant->getConfig($key);
            $configs[$key] = [
                'label' => $label,
                'value' => $value,
                'masked' => $value ? str_repeat('*', max(0, strlen($value) - 4)) . substr($value, -4) : null,
            ];
        }

        return view('tenant.settings.config', compact('tenant', 'configs'));
    }

    public function update(Request $request): RedirectResponse
    {
        $rules = [];
        foreach (array_keys(self::CONFIG_KEYS) as $key) {
            $rules[$key] = ['nullable', 'string', 'max:500'];
        }

        $validated = $request->validate($rules);

        $tenant = $this->manager->get();

        foreach ($validated as $key => $value) {
            if ($value !== null && $value !== '') {
                $tenant->setConfig($key, $value);
            }
        }

        return back()->with('success', 'Azure configuration saved.');
    }
}
