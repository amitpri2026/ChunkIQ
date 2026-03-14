<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Connector extends Model
{
    protected $fillable = ['tenant_id', 'type', 'name', 'settings', 'status'];

    public const TYPES = [
        'sharepoint' => 'SharePoint',
        'teams'      => 'Microsoft Teams',
        'onedrive'   => 'OneDrive',
        'onenote'    => 'OneNote',
    ];

    // Fields required per connector type — used to build the settings form
    public const TYPE_FIELDS = [
        'sharepoint' => [
            'site_url'     => ['label' => 'SharePoint Site URL',    'placeholder' => 'https://contoso.sharepoint.com/sites/HR'],
            'library_name' => ['label' => 'Document Library Name',  'placeholder' => 'Documents (leave blank for default)'],
        ],
        'teams' => [
            'site_url'   => ['label' => 'SharePoint Site URL for Team', 'placeholder' => 'https://contoso.sharepoint.com/sites/TeamName'],
            'team_id'    => ['label' => 'Team ID (optional)',           'placeholder' => 'Azure AD Group / Team ID'],
            'channel_id' => ['label' => 'Channel ID (optional)',        'placeholder' => 'Leave blank to ingest all channels'],
        ],
        'onedrive' => [
            'user_email'  => ['label' => 'User Email (whose OneDrive)', 'placeholder' => 'user@contoso.com'],
            'folder_path' => ['label' => 'Folder Path',                 'placeholder' => '/ (root) or /Documents/Reports'],
        ],
        'onenote' => [
            'site_url'       => ['label' => 'SharePoint Site URL',  'placeholder' => 'https://contoso.sharepoint.com/sites/SiteName'],
            'notebook_id'    => ['label' => 'Notebook ID or Name',  'placeholder' => 'My Notebook (leave blank for all)'],
            'section_filter' => ['label' => 'Section Filter',       'placeholder' => 'Leave blank for all sections'],
        ],
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(PipelineJob::class);
    }

    public function getSettingsDecryptedAttribute(): array
    {
        if (!$this->settings) {
            return [];
        }

        try {
            return json_decode(decrypt($this->settings), true) ?? [];
        } catch (\Exception) {
            return [];
        }
    }

    public function setSettingsAttribute(array|string $value): void
    {
        if (is_array($value)) {
            $this->attributes['settings'] = encrypt(json_encode($value));
        } else {
            $this->attributes['settings'] = $value;
        }
    }

    public function getTypeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
