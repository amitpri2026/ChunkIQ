<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Connector;
use App\Support\TenantManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

        // Persist selected file types (default to all if none submitted)
        $submitted = $request->input('file_types', []);
        $settings['file_types'] = is_array($submitted) && count($submitted)
            ? array_values(array_intersect(array_keys(Connector::FILE_TYPES), $submitted))
            : Connector::DEFAULT_FILE_TYPES;

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

    public function edit(Request $request): View
    {
        $id        = (int) $request->route('connector');
        $tenant    = $this->manager->get();
        $connector = Connector::findOrFail($id);
        abort_if($connector->tenant_id !== $tenant->id, 403);

        $settings = $connector->settings_decrypted;

        return view('tenant.connectors.edit', compact('tenant', 'connector', 'settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $id        = (int) $request->route('connector');
        $tenant    = $this->manager->get();
        $connector = Connector::findOrFail($id);
        abort_if($connector->tenant_id !== $tenant->id, 403);

        $request->validate(['name' => ['required', 'string', 'max:100']]);

        $fields   = Connector::TYPE_FIELDS[$connector->type] ?? [];
        $settings = [];
        foreach (array_keys($fields) as $field) {
            $settings[$field] = $request->input($field, '');
        }

        // Persist selected file types
        $submitted = $request->input('file_types', []);
        $settings['file_types'] = is_array($submitted) && count($submitted)
            ? array_values(array_intersect(array_keys(Connector::FILE_TYPES), $submitted))
            : Connector::DEFAULT_FILE_TYPES;

        $connector->update([
            'name'     => $request->name,
            'settings' => $settings,
            'status'   => $request->input('status', 'active'),
        ]);

        return redirect()->route('tenant.connectors.index', ['tenantSlug' => $tenant->slug])
            ->with('success', 'Connector updated.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $id        = (int) $request->route('connector');
        $tenant    = $this->manager->get();
        $connector = Connector::findOrFail($id);
        abort_if($connector->tenant_id !== $tenant->id, 403);

        $connector->delete();

        return redirect()->route('tenant.connectors.index', ['tenantSlug' => $tenant->slug])
            ->with('success', 'Connector removed.');
    }

    /**
     * Test whether the connector can reach its data source via Microsoft Graph.
     */
    public function testConnector(Request $request): JsonResponse
    {
        $request->validate([
            'type'     => ['required', 'in:' . implode(',', array_keys(Connector::TYPES))],
            'settings' => ['nullable', 'array'],
        ]);

        $tenant       = $this->manager->get();
        $tenantId     = $tenant->getConfig('azure_tenant_id');
        $clientId     = $tenant->getConfig('azure_client_id');
        $clientSecret = $tenant->getConfig('azure_client_secret');

        if (!$tenantId || !$clientId || !$clientSecret) {
            return response()->json([
                'success' => false,
                'message' => 'Azure App Registration credentials are not configured. Please complete the Azure Configuration wizard first.',
            ]);
        }

        // Acquire an access token
        $tokenResponse = Http::asForm()->timeout(10)->post(
            "https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/token",
            [
                'grant_type'    => 'client_credentials',
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'scope'         => 'https://graph.microsoft.com/.default',
            ]
        );

        if (!$tokenResponse->successful() || empty($tokenResponse->json('access_token'))) {
            $err = $tokenResponse->json('error_description') ?? $tokenResponse->body();
            return response()->json(['success' => false, 'message' => 'Could not obtain access token: ' . $err]);
        }

        $token    = $tokenResponse->json('access_token');
        $type     = $request->input('type');
        $settings = $request->input('settings', []);

        try {
            [$success, $message] = match ($type) {
                'sharepoint' => $this->testSharepoint($token, $settings),
                'teams'      => $this->testTeams($token, $settings),
                'onedrive'   => $this->testOneDrive($token, $settings),
                'onenote'    => $this->testOneNote($token, $settings),
                default      => [false, 'Unknown connector type.'],
            };
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }

        return response()->json(compact('success', 'message'));
    }

    // ── Private test helpers ───────────────────────────────────────────────────

    private function testSharepoint(string $token, array $s): array
    {
        $siteUrl = trim($s['site_url'] ?? '');
        if (!$siteUrl) {
            return [false, 'Please enter a SharePoint Site URL first.'];
        }

        $parsed = parse_url($siteUrl);
        $host   = $parsed['host'] ?? '';
        $path   = ltrim($parsed['path'] ?? '', '/');

        $url = $path
            ? "https://graph.microsoft.com/v1.0/sites/{$host}:/{$path}"
            : "https://graph.microsoft.com/v1.0/sites/{$host}";

        $r = Http::withToken($token)->timeout(10)->get($url);

        if ($r->successful()) {
            $name = $r->json('displayName') ?? $r->json('name') ?? 'site';
            return [true, "Connected. Site found: \"{$name}\"."];
        }

        $err = $r->json('error.message') ?? $r->body();
        return [false, "Could not reach SharePoint site: {$err}"];
    }

    private function testTeams(string $token, array $s): array
    {
        $teamId = trim($s['team_id'] ?? '');

        if ($teamId) {
            $r = Http::withToken($token)->timeout(10)->get("https://graph.microsoft.com/v1.0/teams/{$teamId}");
            if ($r->successful()) {
                $name = $r->json('displayName') ?? $teamId;
                return [true, "Connected. Team found: \"{$name}\"."];
            }
            $err = $r->json('error.message') ?? $r->body();
            return [false, "Could not reach Team: {$err}"];
        }

        // No team ID — just verify the app can list groups (which backs Teams)
        $r = Http::withToken($token)->timeout(10)->get('https://graph.microsoft.com/v1.0/groups?$top=1&$select=id,displayName');
        if ($r->successful()) {
            return [true, 'App Registration can access Microsoft Teams groups.'];
        }
        $err = $r->json('error.message') ?? $r->body();
        return [false, "Could not access Teams/groups: {$err}"];
    }

    private function testOneDrive(string $token, array $s): array
    {
        $email = trim($s['user_email'] ?? '');
        if (!$email) {
            return [false, 'Please enter a User Email first.'];
        }

        $r = Http::withToken($token)->timeout(10)->get("https://graph.microsoft.com/v1.0/users/{$email}/drive");
        if ($r->successful()) {
            $name = $r->json('owner.user.displayName') ?? $email;
            return [true, "Connected. OneDrive for \"{$name}\" is accessible."];
        }
        $err = $r->json('error.message') ?? $r->body();
        return [false, "Could not reach OneDrive for {$email}: {$err}"];
    }

    private function testOneNote(string $token, array $s): array
    {
        $siteUrl = trim($s['site_url'] ?? '');
        if (!$siteUrl) {
            return [false, 'Please enter a SharePoint Site URL first.'];
        }

        $parsed = parse_url($siteUrl);
        $host   = $parsed['host'] ?? '';
        $path   = ltrim($parsed['path'] ?? '', '/');

        $siteUrl = $path
            ? "https://graph.microsoft.com/v1.0/sites/{$host}:/{$path}"
            : "https://graph.microsoft.com/v1.0/sites/{$host}";

        $siteRes = Http::withToken($token)->timeout(10)->get($siteUrl);
        if (!$siteRes->successful()) {
            $err = $siteRes->json('error.message') ?? $siteRes->body();
            return [false, "Could not reach SharePoint site: {$err}"];
        }

        $siteId = $siteRes->json('id');
        $r      = Http::withToken($token)->timeout(10)->get("https://graph.microsoft.com/v1.0/sites/{$siteId}/onenote/notebooks?\$top=1");
        if ($r->successful()) {
            $count = count($r->json('value') ?? []);
            return [true, "Connected. Found {$count} OneNote notebook(s) on the site."];
        }
        $err = $r->json('error.message') ?? $r->body();
        return [false, "Could not access OneNote notebooks: {$err}"];
    }
}
