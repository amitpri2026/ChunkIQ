<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionRequest extends Model
{
    protected $fillable = [
        'user_id', 'tenant_id', 'plan',
        'name', 'email', 'company', 'message', 'status',
    ];

    public const STATUSES = [
        'pending'   => ['label' => 'Pending',   'color' => 'yellow'],
        'contacted' => ['label' => 'Contacted',  'color' => 'blue'],
        'converted' => ['label' => 'Converted',  'color' => 'green'],
        'rejected'  => ['label' => 'Rejected',   'color' => 'red'],
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status]['label'] ?? ucfirst($this->status);
    }

    public function statusColor(): string
    {
        return self::STATUSES[$this->status]['color'] ?? 'gray';
    }

    public function planLabel(): string
    {
        return Tenant::PLANS[$this->plan]['name'] ?? ucfirst($this->plan);
    }
}
