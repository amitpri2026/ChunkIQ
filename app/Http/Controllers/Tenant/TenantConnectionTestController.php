<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Support\TenantManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class TenantConnectionTestController extends Controller
{
    public function __construct(private TenantManager $manager) {}

    public function testAppRegistration(): JsonResponse
    {
        $tenant       = $this->manager->get();
        $tenantId     = $tenant->getConfig('azure_tenant_id');
        $clientId     = $tenant->getConfig('azure_client_id');
        $clientSecret = $tenant->getConfig('azure_client_secret');

        if (!$tenantId || !$clientId || !$clientSecret) {
            return response()->json(['success' => false, 'message' => 'Credentials not fully configured yet.']);
        }

        try {
            $response = Http::asForm()->timeout(10)->post(
                "https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/token",
                [
                    'grant_type'    => 'client_credentials',
                    'client_id'     => $clientId,
                    'client_secret' => $clientSecret,
                    'scope'         => 'https://graph.microsoft.com/.default',
                ]
            );

            if ($response->successful() && $response->json('access_token')) {
                return response()->json(['success' => true, 'message' => 'Connected. Token acquired from Azure AD successfully.']);
            }

            $error = $response->json('error_description') ?? $response->json('error') ?? 'Authentication failed.';
            return response()->json(['success' => false, 'message' => $error]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function testStorage(): JsonResponse
    {
        $tenant  = $this->manager->get();
        $account = $tenant->getConfig('adls_account_name');
        $key     = $tenant->getConfig('adls_key');

        if (!$account || !$key) {
            return response()->json(['success' => false, 'message' => 'Storage credentials not fully configured yet.']);
        }

        try {
            $date    = gmdate('D, d M Y H:i:s T');
            $version = '2020-10-02';

            $canonicalizedHeaders  = "x-ms-date:{$date}\nx-ms-version:{$version}";
            $canonicalizedResource = "/{$account}/\ncomp:list";

            $stringToSign = "GET\n\n\n\n\n\n\n\n\n\n\n\n{$canonicalizedHeaders}\n{$canonicalizedResource}";
            $signature    = base64_encode(hash_hmac('sha256', $stringToSign, base64_decode($key), true));

            $response = Http::timeout(10)->withHeaders([
                'x-ms-date'     => $date,
                'x-ms-version'  => $version,
                'Authorization' => "SharedKey {$account}:{$signature}",
            ])->get("https://{$account}.blob.core.windows.net/?comp=list");

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Connected. Storage account is accessible.']);
            }

            return response()->json(['success' => false, 'message' => 'HTTP ' . $response->status() . ' — check account name and key.']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function testSearch(): JsonResponse
    {
        $tenant   = $this->manager->get();
        $endpoint = $tenant->getConfig('ai_search_endpoint');
        $key      = $tenant->getConfig('ai_search_key');

        if (!$endpoint || !$key) {
            return response()->json(['success' => false, 'message' => 'Search credentials not fully configured yet.']);
        }

        try {
            $endpoint = rtrim($endpoint, '/');
            $response = Http::timeout(10)->withHeaders([
                'api-key' => $key,
            ])->get("{$endpoint}/indexes?api-version=2021-04-30-Preview");

            if ($response->successful()) {
                $count = count($response->json('value') ?? []);
                return response()->json(['success' => true, 'message' => "Connected. Found {$count} index(es) on the search service."]);
            }

            return response()->json(['success' => false, 'message' => 'HTTP ' . $response->status() . ' — check endpoint URL and admin key.']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
