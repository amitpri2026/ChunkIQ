<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    protected $fillable = ['name', 'slug', 'owner_id', 'description'];

    // Reserved slugs that cannot be used as tenant subdomains
    public const RESERVED_SLUGS = [
        'www', 'api', 'app', 'admin', 'mail', 'smtp', 'ftp',
        'cdn', 'static', 'assets', 'login', 'register', 'auth',
        'dashboard', 'portal', 'help', 'support', 'billing',
        'status', 'dev', 'staging', 'test', 'demo',
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
        $base = config('app.url');
        $parsed = parse_url($base);
        $scheme = $parsed['scheme'] ?? 'https';
        $host = $parsed['host'] ?? 'chunkiq.com';

        return $scheme . '://' . $this->slug . '.' . $host . ($path ? '/' . ltrim($path, '/') : '');
    }
}
