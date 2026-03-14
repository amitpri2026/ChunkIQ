<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TenantInvite extends Model
{
    protected $fillable = ['tenant_id', 'created_by', 'token', 'role', 'expires_at', 'used_at'];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at'    => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }

    public function isValid(): bool
    {
        return !$this->isUsed() && !$this->isExpired();
    }

    public static function generate(Tenant $tenant, User $creator, string $role = 'user'): self
    {
        return self::create([
            'tenant_id'  => $tenant->id,
            'created_by' => $creator->id,
            'token'      => Str::random(48),
            'role'       => $role,
            'expires_at' => now()->addDays(7),
        ]);
    }
}
