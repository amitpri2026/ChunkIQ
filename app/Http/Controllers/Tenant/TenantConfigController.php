<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Support\TenantManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TenantConfigController extends Controller
{
    // All keys users can configure — maps config key to display label
    public const CONFIG_KEYS = [
        // Azure identity (App Registration)
        'azure_tenant_id'             => 'Directory (Tenant) ID',
        'azure_client_id'             => 'Application (Client) ID',
        'azure_client_secret'         => 'Client Secret Value',
        // ADLS Gen2
        'adls_account_name'           => 'Storage Account Name',
        'adls_container'              => 'Raw Container Name',
        'adls_enriched_container'     => 'Enriched Container Name',
        'adls_logs_container'         => 'Logs Container Name',
        'adls_key'                    => 'Storage Account Key',
        // Azure AI Search
        'ai_search_endpoint'          => 'AI Search Endpoint URL',
        'ai_search_key'               => 'AI Search Admin Key',
        'ai_search_index_name'        => 'Index Name',
    ];

    public const STEPS = [
        1 => [
            'title'       => 'App Registration',
            'description' => 'Azure AD (Entra ID) application credentials. ChunkIQ uses these to read your Microsoft 365 data (SharePoint, Teams, OneDrive, OneNote) via the Microsoft Graph API.',
            'keys'        => ['azure_tenant_id', 'azure_client_id', 'azure_client_secret'],
            'permissions' => [
                ['name' => 'Sites.Read.All',          'type' => 'Application', 'reason' => 'Read all SharePoint sites and document libraries'],
                ['name' => 'Files.Read.All',          'type' => 'Application', 'reason' => 'Read files from SharePoint, Teams and OneDrive'],
                ['name' => 'Notes.Read.All',          'type' => 'Application', 'reason' => 'Read all OneNote notebooks and pages'],
                ['name' => 'User.Read.All',           'type' => 'Application', 'reason' => 'Resolve user identities for OneDrive access'],
                ['name' => 'Team.ReadBasic.All',      'type' => 'Application', 'reason' => 'List all Teams in the organisation'],
                ['name' => 'ChannelMessage.Read.All', 'type' => 'Application', 'reason' => 'Read Teams channel messages (optional)'],
            ],
            'setup_steps' => [
                'Open Azure Portal → Azure Active Directory → App Registrations → New registration.',
                'Name it (e.g. ChunkIQ-Connector), choose "Accounts in this org only", leave Redirect URI blank.',
                'Copy the Directory (tenant) ID from the Overview page.',
                'Copy the Application (client) ID from the Overview page.',
                'Go to Certificates & secrets → New client secret → set expiry to 24 months → copy the Value immediately (shown once only).',
                'Go to API permissions → Add a permission → Microsoft Graph → Application permissions → add all permissions listed below.',
                'Click "Grant admin consent for [your org]" — required for app-only access.',
            ],
        ],
        2 => [
            'title'       => 'Azure Storage',
            'description' => 'Azure Data Lake Storage Gen2 account in your subscription. ChunkIQ writes raw ingested files, extracted content, and processing logs here. Your data never leaves your Azure tenant.',
            'keys'        => ['adls_account_name', 'adls_container', 'adls_enriched_container', 'adls_logs_container', 'adls_key'],
            'permissions' => [],
            'setup_steps' => [
                'Open Azure Portal → Storage accounts → Create.',
                'On the Advanced tab, enable Hierarchical namespace — this makes it ADLS Gen2.',
                'Create three containers (Private access): raw, enriched, logs.',
                'Go to Security + networking → Access keys → copy Key 1.',
                'Enter the account name, container names (raw / enriched / logs), and the key below.',
            ],
        ],
        3 => [
            'title'       => 'Azure AI Search',
            'description' => 'Your Azure AI Search service hosts the document index that powers semantic search in ChunkIQ. The index is created automatically by ChunkIQ on the first processing run — you just need to provide the service credentials.',
            'keys'        => ['ai_search_endpoint', 'ai_search_key', 'ai_search_index_name'],
            'permissions' => [],
            'setup_steps' => [
                'Open Azure Portal → AI Search → Create.',
                'Use the same resource group and region as your storage account.',
                'Pricing tier: Free (evaluation) or Basic (production).',
                'Once provisioned, copy the Url from the Overview page (e.g. https://<name>.search.windows.net).',
                'Go to Settings → Keys → copy the Primary admin key.',
                'Choose an index name (e.g. chunkiq-documents) — ChunkIQ will create it automatically.',
            ],
        ],
    ];

    public function __construct(private TenantManager $manager) {}

    public function edit(Request $request): View
    {
        $tenant = $this->manager->get();

        $totalSteps  = count(self::STEPS);
        $currentStep = max(1, min((int) $request->query('step', 1), $totalSteps));
        $step        = self::STEPS[$currentStep];

        $configs = [];
        foreach (self::CONFIG_KEYS as $key => $label) {
            $value = $tenant->getConfig($key);
            $configs[$key] = [
                'label'  => $label,
                'value'  => $value,
                'masked' => $value ? str_repeat('*', max(0, strlen($value) - 4)) . substr($value, -4) : null,
            ];
        }

        $completedSteps = $this->getCompletedSteps($tenant);

        return view('tenant.settings.config', compact(
            'tenant', 'configs', 'currentStep', 'step', 'totalSteps', 'completedSteps'
        ));
    }

    public function update(Request $request): RedirectResponse
    {
        $rules = [];
        foreach (array_keys(self::CONFIG_KEYS) as $key) {
            $rules[$key] = ['nullable', 'string', 'max:500'];
        }

        $validated   = $request->validate($rules);
        $currentStep = max(1, min((int) $request->input('current_step', 1), count(self::STEPS)));
        $tenant      = $this->manager->get();

        foreach ($validated as $key => $value) {
            if ($value !== null && $value !== '') {
                $tenant->setConfig($key, $value);
            }
        }

        $slug      = $tenant->slug;
        $nextStep  = $currentStep + 1;
        $lastStep  = count(self::STEPS);
        $stepTitle = self::STEPS[$currentStep]['title'];

        if ($currentStep < $lastStep) {
            return redirect()
                ->route('tenant.config.edit', ['tenantSlug' => $slug, 'step' => $nextStep])
                ->with('success', "{$stepTitle} saved. Continue with the next step.");
        }

        // Final step — notify super admins that tenant is fully configured
        \App\Models\User::where('is_super_admin', true)->each(
            fn($admin) => $admin->notify(new \App\Notifications\TenantConfiguredNotification($tenant))
        );
        try {
            \Illuminate\Support\Facades\Mail::to(auth()->user())->send(new \App\Mail\TenantConfiguredMail($tenant));
        } catch (\Exception $e) {}

        return redirect()
            ->route('tenant.config.edit', ['tenantSlug' => $slug, 'step' => $currentStep])
            ->with('success', 'All configuration saved successfully. You are ready to run your first sync.');
    }

    private function getCompletedSteps($tenant): array
    {
        $completed = [];
        foreach (self::STEPS as $num => $step) {
            foreach ($step['keys'] as $key) {
                if ($tenant->getConfig($key)) {
                    $completed[] = $num;
                    break;
                }
            }
        }
        return $completed;
    }
}
