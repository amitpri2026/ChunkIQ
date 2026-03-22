<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    protected $fillable = [
        'name', 'slug', 'owner_id', 'description',
        'plan', 'documents_processed', 'connector_limit_override', 'document_limit_override',
    ];

    protected $casts = [
        'documents_processed'     => 'integer',
        'connector_limit_override' => 'integer',
        'document_limit_override'  => 'integer',
    ];

    // ── Plan definitions ──────────────────────────────────────────────────────
    public const PLANS = [
        'free' => [
            'name'           => 'Free',
            'price'          => 0,
            'connectors'     => 1,
            'documents'      => 100,
            'scheduled_jobs' => false,
            'color'          => 'gray',
        ],
        'starter' => [
            'name'           => 'Starter',
            'price'          => 299,
            'connectors'     => null,   // unlimited
            'documents'      => 50000,
            'scheduled_jobs' => true,
            'color'          => 'blue',
        ],
        'enterprise' => [
            'name'           => 'Enterprise',
            'price'          => null,   // custom
            'connectors'     => null,   // unlimited
            'documents'      => null,   // unlimited
            'scheduled_jobs' => true,
            'color'          => 'purple',
        ],
    ];

    public function planLabel(): string
    {
        return self::PLANS[$this->plan]['name'] ?? ucfirst($this->plan);
    }

    public function planColor(): string
    {
        return self::PLANS[$this->plan]['color'] ?? 'gray';
    }

    /** Effective connector limit (override takes precedence over plan default). */
    public function connectorLimit(): ?int
    {
        if ($this->connector_limit_override !== null) {
            return $this->connector_limit_override;
        }
        return self::PLANS[$this->plan]['connectors'] ?? null;
    }

    /** Effective document limit (override takes precedence over plan default). */
    public function documentLimit(): ?int
    {
        if ($this->document_limit_override !== null) {
            return $this->document_limit_override;
        }
        $limit = self::PLANS[$this->plan]['documents'] ?? null;
        return $limit;
    }

    public function allowsScheduledJobs(): bool
    {
        return self::PLANS[$this->plan]['scheduled_jobs'] ?? false;
    }

    public function atConnectorLimit(): bool
    {
        $limit = $this->connectorLimit();
        return $limit !== null && $this->connectors()->count() >= $limit;
    }

    public function atDocumentLimit(): bool
    {
        $limit = $this->documentLimit();
        return $limit !== null && $this->documents_processed >= $limit;
    }

    // Reserved slugs that cannot be used as tenant subdomains
    public const RESERVED_SLUGS = [
        // Infrastructure
        'www', 'api', 'app', 'admin', 'mail', 'smtp', 'ftp', 'ssh',
        'cdn', 'static', 'assets', 'media', 'files', 'uploads',
        // Auth & portal
        'login', 'logout', 'register', 'auth', 'oauth', 'sso',
        'dashboard', 'portal', 'account', 'profile', 'settings',
        // Product
        'help', 'support', 'billing', 'pricing', 'docs', 'documentation',
        'status', 'health', 'monitor', 'metrics',
        // Environments
        'dev', 'development', 'staging', 'test', 'testing', 'demo', 'sandbox', 'prod', 'production',
        // Generic / reserved words users might try
        'tenant', 'tenants', 'workspace', 'workspaces', 'server', 'servers',
        'team', 'teams', 'org', 'organization', 'organisations', 'organizations', 'company',
        'data', 'database', 'db', 'store', 'storage',
        'user', 'users', 'member', 'members', 'guest', 'guests', 'staff',
        'public', 'private', 'internal', 'external', 'global', 'default',
        'system', 'systems', 'platform', 'core', 'root', 'base',
        'null', 'undefined', 'none', 'test1', 'test2', 'sample', 'example',
        'chunkiq', 'chunk', 'iq', 'microsoft', 'azure', 'sharepoint',
        'new', 'create', 'edit', 'delete', 'update', 'manage',
        'secure', 'security', 'ssl', 'tls', 'vpn',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function admins(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps()
            ->wherePivot('role', 'admin');
    }

    public function invites(): HasMany
    {
        return $this->hasMany(TenantInvite::class);
    }

    public function configs(): HasMany
    {
        return $this->hasMany(TenantConfig::class);
    }

    public function connectors(): HasMany
    {
        return $this->hasMany(Connector::class);
    }

    public function pipelineJobs(): HasMany
    {
        return $this->hasMany(PipelineJob::class);
    }

    public function getConfig(string $key): ?string
    {
        $config = $this->configs()->where('key', $key)->first();

        return $config ? decrypt($config->value) : null;
    }

    public function setConfig(string $key, string $value): void
    {
        $this->configs()->updateOrCreate(
            ['key' => $key],
            ['value' => encrypt($value)]
        );
    }

    public function hasUser(User $user): bool
    {
        return $this->users()->where('user_id', $user->id)->exists();
    }

    public function userRole(User $user): ?string
    {
        $pivot = $this->users()->where('user_id', $user->id)->first();

        return $pivot?->pivot->role;
    }

    public function isAdmin(User $user): bool
    {
        return $this->userRole($user) === 'admin';
    }

    // Build the full subdomain URL for this tenant
    public function url(string $path = ''): string
    {
        $base   = config('app.url', 'https://chunkiq.com');
        $parsed = parse_url($base);
        $scheme = $parsed['scheme'] ?? 'https';
        $port   = isset($parsed['port']) ? ':' . $parsed['port'] : '';
        $domain = config('app.tenant_domain', 'chunkiq.com');

        return $scheme . '://' . $this->slug . '.' . $domain . $port . ($path ? '/' . ltrim($path, '/') : '');
    }
}
