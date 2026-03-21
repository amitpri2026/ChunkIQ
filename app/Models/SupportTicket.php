<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportTicket extends Model
{
    protected $fillable = ['user_id', 'subject', 'message', 'priority', 'status'];

    public const PRIORITIES = ['low', 'medium', 'high'];
    public const STATUSES   = ['open', 'in_progress', 'closed'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function replies(): HasMany { return $this->hasMany(TicketReply::class, 'ticket_id'); }

    public function priorityColor(): string
    {
        return match($this->priority) {
            'high'   => 'red',
            'medium' => 'yellow',
            'low'    => 'green',
            default  => 'gray',
        };
    }

    public function statusColor(): string
    {
        return match($this->status) {
            'open'        => 'blue',
            'in_progress' => 'yellow',
            'closed'      => 'gray',
            default       => 'gray',
        };
    }
}
