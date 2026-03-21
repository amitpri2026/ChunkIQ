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

    public const STEPS = [
        1 => [
            'title'       => 'App Registration',
            'description' => 'Azure AD (Entra ID) application credentials used to authenticate all Microsoft 365 API calls.',
            'keys'        => ['azure_tenant_id', 'azure_client_id', 'azure_client_secret', 'azure_subscription_id'],
            'permissions' => [
                ['name' => 'Sites.Read.All',          'type' => 'Application', 'reason' => 'Read all SharePoint sites and document libraries'],
                ['name' => 'Files.Read.All',          'type' => 'Application', 'reason' => 'Read files from SharePoint, Teams and OneDrive'],
                ['name' => 'ChannelMessage.Read.All', 'type' => 'Application', 'reason' => 'Read all Teams channel messages'],
                ['name' => 'Team.ReadBasic.All',      'type' => 'Application', 'reason' => 'List all Teams in the organisation'],
                ['name' => 'Notes.Read.All',          'type' => 'Application', 'reason' => 'Read all OneNote notebooks'],
                ['name' => 'User.Read.All',           'type' => 'Application', 'reason' => 'Resolve user identities attached to documents'],
            ],
            'setup_steps' => [
                'Open Azure Portal → Azure Active Directory (Entra ID) → App Registrations.',
                'Click "New Registration", name it (e.g. ChunkIQ), choose "Accounts in this org only".',
                'Copy the Application (client) ID → paste as Client ID below.',
                'Copy the Directory (tenant) ID → paste as Tenant ID below.',
                'Under Certificates & secrets → New client secret → copy the Value immediately.',
                'Under API permissions → Add a permission → Microsoft Graph → Application permissions → add all permissions listed below.',
                'Click "Grant admin consent for [your org]" to activate the permissions.',
            ],
        ],
        2 => [
            'title'       => 'Azure Storage',
            'description' => 'Azure Data Lake Storage Gen2 where ChunkIQ stores processed and chunked documents.',
            'keys'        => ['adls_account_name', 'adls_container', 'adls_key'],
            'permissions' => [],
            'setup_steps' => [
                'Create a Storage Account in Azure Portal with "Hierarchical namespace" enabled (required for ADLS Gen2).',
                'Create a container (e.g. chunkiq-docs) inside the storage account.',
                'Go to the storage account → Access keys → copy Key1 or Key2.',
            ],
        ],
        3 => [
            'title'       => 'Teams',
            'description' => 'Azure Function App that ingests Microsoft Teams channel messages and shared files.',
            'keys'        => ['fa_teams_url', 'fa_teams_key'],
            'permissions' => [
                ['name' => 'ChannelMessage.Read.All', 'type' => 'Application', 'reason' => 'Read all Teams channel messages'],
                ['name' => 'Team.ReadBasic.All',      'type' => 'Application', 'reason' => 'List available Teams'],
                ['name' => 'Files.Read.All',          'type' => 'Application', 'reason' => 'Read files shared in Teams channels'],
            ],
            'setup_steps' => [
                'Deploy the ChunkIQ Teams Function App to your Azure subscription.',
                'Open the Function App → Overview → copy the URL.',
                'Open Functions → your function → Get function URL → copy the key part after "?code=".',
            ],
        ],
        4 => [
            'title'       => 'SharePoint',
            'description' => 'Azure Function App that ingests SharePoint Online document libraries and list items.',
            'keys'        => ['fa_sharepoint_url', 'fa_sharepoint_key'],
            'permissions' => [
                ['name' => 'Sites.Read.All', 'type' => 'Application', 'reason' => 'Read all SharePoint site contents and metadata'],
                ['name' => 'Files.Read.All', 'type' => 'Application', 'reason' => 'Read documents inside libraries'],
            ],
            'setup_steps' => [
                'Deploy the ChunkIQ SharePoint Function App to your Azure subscription.',
                'Open the Function App → Overview → copy the URL.',
                'Open Functions → your function → Get function URL → copy the key part after "?code=".',
            ],
        ],
        5 => [
            'title'       => 'OneDrive',
            'description' => 'Azure Function App that ingests OneDrive for Business files across your organisation.',
            'keys'        => ['fa_onedrive_url', 'fa_onedrive_key'],
            'permissions' => [
                ['name' => 'Files.Read.All', 'type' => 'Application', 'reason' => 'Read all OneDrive files across the organisation'],
                ['name' => 'User.Read.All',  'type' => 'Application', 'reason' => 'Enumerate users to access their OneDrive'],
            ],
            'setup_steps' => [
                'Deploy the ChunkIQ OneDrive Function App to your Azure subscription.',
                'Open the Function App → Overview → copy the URL.',
                'Open Functions → your function → Get function URL → copy the key part after "?code=".',
            ],
        ],
        6 => [
            'title'       => 'OneNote',
            'description' => 'Azure Function App that ingests OneNote notebooks and sections.',
            'keys'        => ['fa_onenote_url', 'fa_onenote_key'],
            'permissions' => [
                ['name' => 'Notes.Read.All', 'type' => 'Application', 'reason' => 'Read all OneNote notebooks across the organisation'],
                ['name' => 'Sites.Read.All', 'type' => 'Application', 'reason' => 'Required for notebooks stored in SharePoint-backed sites'],
            ],
            'setup_steps' => [
                'Deploy the ChunkIQ OneNote Function App to your Azure subscription.',
                'Open the Function App → Overview → copy the URL.',
                'Open Functions → your function → Get function URL → copy the key part after "?code=".',
            ],
        ],
        7 => [
            'title'       => 'Processor',
            'description' => 'Azure Function App that chunks, embeds, and indexes documents from ADLS into Azure AI Search.',
            'keys'        => ['fa_processor_url', 'fa_processor_key'],
            'permissions' => [],
            'setup_steps' => [
                'Deploy the ChunkIQ Processor Function App to your Azure subscription.',
                'Open the Function App → Overview → copy the URL.',
                'Open Functions → your function → Get function URL → copy the key part after "?code=".',
                'Ensure this Function App has access to the same ADLS account configured in Step 2.',
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

        // On step 7 (final step) completion, notify super admins
        \App\Models\User::where('is_super_admin', true)->each(
            fn($admin) => $admin->notify(new \App\Notifications\TenantConfiguredNotification($tenant))
        );
        try {
            \Illuminate\Support\Facades\Mail::to(auth()->user())->send(new \App\Mail\TenantConfiguredMail($tenant));
        } catch (\Exception $e) {}

        return redirect()
            ->route('tenant.config.edit', ['tenantSlug' => $slug, 'step' => $currentStep])
            ->with('success', 'All configuration saved successfully.');
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
