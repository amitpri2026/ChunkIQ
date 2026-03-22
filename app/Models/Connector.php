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

    // File types users can choose to include during ingestion
    public const FILE_TYPES = [
        'pdf'  => ['label' => 'PDF',                 'ext' => '.pdf'],
        'docx' => ['label' => 'Word Document',        'ext' => '.docx'],
        'doc'  => ['label' => 'Word (Legacy)',         'ext' => '.doc'],
        'pptx' => ['label' => 'PowerPoint',           'ext' => '.pptx'],
        'ppt'  => ['label' => 'PowerPoint (Legacy)',  'ext' => '.ppt'],
        'xlsx' => ['label' => 'Excel Spreadsheet',    'ext' => '.xlsx'],
        'xls'  => ['label' => 'Excel (Legacy)',        'ext' => '.xls'],
        'txt'  => ['label' => 'Plain Text',            'ext' => '.txt'],
        'md'   => ['label' => 'Markdown',              'ext' => '.md'],
        'html' => ['label' => 'HTML',                  'ext' => '.html'],
        'msg'  => ['label' => 'Outlook Message',       'ext' => '.msg'],
        'csv'  => ['label' => 'CSV',                   'ext' => '.csv'],
    ];

    // Default file types pre-selected for new connectors
    public const DEFAULT_FILE_TYPES = ['pdf', 'docx', 'pptx', 'xlsx', 'txt'];

    // Fields required per connector type — used to build the settings form
    public const TYPE_FIELDS = [
        'sharepoint' => [
            'site_url'     => ['label' => 'SharePoint Site URL',   'placeholder' => 'https://contoso.sharepoint.com/sites/HR',            'hint' => 'Full URL of the SharePoint site to connect to.'],
            'library_name' => ['label' => 'Document Library Name', 'placeholder' => 'Documents (leave blank for default)',                'hint' => 'Name of the document library. Leave blank to use the default "Documents" library.'],
        ],
        'teams' => [
            'site_url'   => ['label' => 'SharePoint Site URL for Team', 'placeholder' => 'https://contoso.sharepoint.com/sites/TeamName', 'hint' => 'Each Teams team has a backing SharePoint site — paste its URL here.'],
            'team_id'    => ['label' => 'Team ID (optional)',           'placeholder' => 'Azure AD Group / Team ID',                      'hint' => 'Leave blank to use all teams accessible via the App Registration.'],
            'channel_id' => ['label' => 'Channel ID (optional)',        'placeholder' => 'Leave blank to ingest all channels',            'hint' => 'Restrict ingestion to a specific channel, or leave blank for all.'],
        ],
        'onedrive' => [
            'user_email'  => ['label' => 'User Email',  'placeholder' => 'user@contoso.com',               'hint' => 'Email of the user whose OneDrive files you want to index.'],
            'folder_path' => ['label' => 'Folder Path', 'placeholder' => '/ (root) or /Documents/Reports', 'hint' => 'Path within the OneDrive to index. Use / to index everything from the root.'],
        ],
        'onenote' => [
            'site_url'       => ['label' => 'SharePoint Site URL', 'placeholder' => 'https://contoso.sharepoint.com/sites/SiteName', 'hint' => 'URL of the SharePoint site where the OneNote notebook is stored.'],
            'notebook_id'    => ['label' => 'Notebook Name or ID', 'placeholder' => 'My Notebook (leave blank for all)',             'hint' => 'Target a specific notebook by name or ID, or leave blank for all notebooks.'],
            'section_filter' => ['label' => 'Section Filter',      'placeholder' => 'Leave blank for all sections',                 'hint' => 'Enter a section name to limit ingestion to that section only.'],
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
