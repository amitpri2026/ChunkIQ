<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PipelineJob extends Model
{
    protected $table = 'pipeline_jobs';

    protected $fillable = [
        'tenant_id', 'connector_id', 'triggered_by', 'name',
        'type', 'status', 'config', 'logs', 'callback_token',
        'started_at', 'finished_at',
    ];

    protected $casts = [
        'started_at'  => 'datetime',
        'finished_at' => 'datetime',
    ];

    public const TYPES = [
        'ingestion'  => 'Ingestion (Source → ADLS)',
        'processing' => 'Processing (ADLS → Processed)',
    ];

    public const STATUSES = [
        'pending'   => ['label' => 'Pending',   'color' => 'gray'],
        'running'   => ['label' => 'Running',   'color' => 'blue'],
        'succeeded' => ['label' => 'Succeeded', 'color' => 'green'],
        'failed'    => ['label' => 'Failed',    'color' => 'red'],
        'cancelled' => ['label' => 'Cancelled', 'color' => 'yellow'],
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function connector(): BelongsTo
    {
        return $this->belongsTo(Connector::class);
    }

    public function triggeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'triggered_by');
    }

    public function getConfigDecoded(): array
    {
        return $this->config ? json_decode($this->config, true) : [];
    }

    public function getStatusBadgeColor(): string
    {
        return self::STATUSES[$this->status]['color'] ?? 'gray';
    }

    public function appendLog(string $line): void
    {
        $this->logs = ($this->logs ?? '') . '[' . now()->toDateTimeString() . '] ' . $line . "\n";
        $this->save();
    }

    public function markRunning(): void
    {
        $this->update([
            'status'     => 'running',
            'started_at' => now(),
            'logs'       => '[' . now()->toDateTimeString() . '] Job triggered.' . "\n",
        ]);
    }

    public function markFinished(string $status, string $summary = '', string $detail = ''): void
    {
        $log = $this->logs ?? '';
        if ($summary) {
            $log .= '[' . now()->toDateTimeString() . '] ' . $summary . "\n";
        }
        if ($detail) {
            $log .= "\n--- Azure Function Log ---\n" . $detail . "\n";
        }

        $this->update([
            'status'      => $status,
            'finished_at' => now(),
            'logs'        => $log,
        ]);
    }

    public static function generateCallbackToken(): string
    {
        return Str::random(48);
    }
}
