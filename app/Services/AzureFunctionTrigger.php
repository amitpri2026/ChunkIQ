<?php

namespace App\Services;

use App\Models\PipelineJob;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AzureFunctionTrigger
{
    // Function App URL config keys per connector type (stored in tenant_configs)
    private const URL_KEYS = [
        'sharepoint' => 'fa_sharepoint_url',
        'teams'      => 'fa_teams_url',
        'onedrive'   => 'fa_onedrive_url',
        'onenote'    => 'fa_onenote_url',
        'processing' => 'fa_processor_url',
    ];

    private const KEY_KEYS = [
        'sharepoint' => 'fa_sharepoint_key',
        'teams'      => 'fa_teams_key',
        'onedrive'   => 'fa_onedrive_key',
        'onenote'    => 'fa_onenote_key',
        'processing' => 'fa_processor_key',
    ];

    public function trigger(PipelineJob $job): bool
    {
        $tenant    = $job->tenant;
        $connector = $job->connector;

        // Determine which Function App to call
        $typeKey = $job->type === 'processing' ? 'processing' : ($connector?->type ?? 'processing');

        $functionUrl = $tenant->getConfig(self::URL_KEYS[$typeKey] ?? '');
        $functionKey = $tenant->getConfig(self::KEY_KEYS[$typeKey] ?? '');

        if (!$functionUrl) {
            $job->markFinished('failed', 'Function App URL not configured for type: ' . $typeKey);
            return false;
        }

        // Build payload passed to the Azure Function
        $payload = [
            'job_id'         => $job->id,
            'job_type'       => $job->type,
            'connector_type' => $connector?->type ?? 'processing',
            'job_config'     => $job->getConfigDecoded(),
            'connector'      => $connector ? $connector->settings_decrypted : [],
            'azure_config' => [
                'tenant_id'       => $tenant->getConfig('azure_tenant_id'),
                'client_id'       => $tenant->getConfig('azure_client_id'),
                'client_secret'   => $tenant->getConfig('azure_client_secret'),
                'subscription_id' => $tenant->getConfig('azure_subscription_id'),
                'adls_account'    => $tenant->getConfig('adls_account_name'),
                'adls_container'  => $tenant->getConfig('adls_container'),
                'adls_key'        => $tenant->getConfig('adls_key'),
            ],
            'callback_url'   => route('api.jobs.callback', $job->callback_token),
            'callback_token' => $job->callback_token,
        ];

        try {
            $job->markRunning();

            $response = Http::timeout(10)
                ->withHeaders(array_filter([
                    'x-functions-key' => $functionKey,
                    'Content-Type'    => 'application/json',
                ]))
                ->post($functionUrl, $payload);

            if ($response->successful()) {
                $job->appendLog('Function App accepted the trigger (HTTP ' . $response->status() . ').');
                return true;
            }

            $job->markFinished('failed', 'Function App returned HTTP ' . $response->status() . ': ' . $response->body());
            return false;

        } catch (\Exception $e) {
            Log::error('AzureFunctionTrigger failed', ['job_id' => $job->id, 'error' => $e->getMessage()]);
            $job->markFinished('failed', 'Trigger exception: ' . $e->getMessage());
            return false;
        }
    }
}
