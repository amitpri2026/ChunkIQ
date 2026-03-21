<?php

namespace App\Services;

use App\Models\PipelineJob;
use App\Models\SystemConfig;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AzureFunctionTrigger
{
    public function trigger(PipelineJob $job): bool
    {
        $tenant    = $job->tenant;
        $connector = $job->connector;

        // Function App URL and Key are platform-wide (set by super admin via /admin/settings).
        // Users never configure these — ChunkIQ owns and operates the processing engine.
        $functionUrl = SystemConfig::get('fa_url');
        $functionKey = SystemConfig::get('fa_key');

        if (!$functionUrl) {
            $job->markFinished('failed', 'Function App URL not configured. Contact your ChunkIQ administrator.');
            return false;
        }

        $payload = [
            'job_id'         => $job->id,
            'job_type'       => $job->type,
            'connector_type' => $connector?->type ?? 'processing',
            'connector'      => $connector ? $connector->settings_decrypted : [],
            'job_config'     => $job->getConfigDecoded(),

            // User's Azure AD identity — used for cross-tenant Graph API access
            'azure_config' => [
                'tenant_id'               => $tenant->getConfig('azure_tenant_id'),
                'client_id'               => $tenant->getConfig('azure_client_id'),
                'client_secret'           => $tenant->getConfig('azure_client_secret'),
                'adls_account'            => $tenant->getConfig('adls_account_name'),
                'adls_key'                => $tenant->getConfig('adls_key'),
                'adls_container'          => $tenant->getConfig('adls_container') ?: 'raw',
                'adls_enriched_container' => $tenant->getConfig('adls_enriched_container') ?: 'enriched',
                'adls_logs_container'     => $tenant->getConfig('adls_logs_container') ?: 'logs',
            ],

            // User's AI Search service — index lives in their Azure tenant
            'search_config' => [
                'endpoint'   => $tenant->getConfig('ai_search_endpoint'),
                'api_key'    => $tenant->getConfig('ai_search_key'),
                'index_name' => $tenant->getConfig('ai_search_index_name') ?: 'chunkiq-documents',
            ],

            // User's Azure OpenAI service — embeddings and chat
            'openai_config' => [
                'endpoint'             => $tenant->getConfig('openai_endpoint'),
                'api_key'              => $tenant->getConfig('openai_key'),
                'embedding_deployment' => $tenant->getConfig('openai_embedding_deployment') ?: 'text-embedding-3-small',
                'chat_deployment'      => $tenant->getConfig('openai_chat_deployment') ?: 'gpt-4o-mini',
                'api_version'          => '2024-02-01',
                'dimensions'           => 1536,
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
