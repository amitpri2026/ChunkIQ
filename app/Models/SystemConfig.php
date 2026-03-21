<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class SystemConfig extends Model
{
    protected $fillable = ['key', 'value'];

    // Keys whose values are stored encrypted
    public const ENCRYPTED_KEYS = [
        'fa_url',
        'fa_key',
    ];

    // ── Static helpers ────────────────────────────────────────────────────────

    public static function get(string $key): ?string
    {
        $record = static::where('key', $key)->first();
        if (!$record || $record->value === null) {
            return null;
        }

        if (in_array($key, self::ENCRYPTED_KEYS, true)) {
            try {
                return Crypt::decryptString($record->value);
            } catch (\Exception) {
                return null;
            }
        }

        return $record->value;
    }

    public static function set(string $key, string $value): void
    {
        $stored = in_array($key, self::ENCRYPTED_KEYS, true)
            ? Crypt::encryptString($value)
            : $value;

        static::updateOrCreate(['key' => $key], ['value' => $stored]);
    }
}
