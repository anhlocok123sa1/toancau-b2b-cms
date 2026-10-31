<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ComplianceChecklist extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'item_key',
        'title',
        'description',
        'is_verified',
        'verified_at',
        'verified_by',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
            'verified_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['item_key', 'title', 'is_verified', 'metadata'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Helper to safely check if an item is legally verified
     */
    public static function isItemVerified(string $key): bool
    {
        return (bool) static::where('item_key', $key)->where('is_verified', true)->exists();
    }

    /**
     * Get safe public metadata (guaranteeing sensitive subnets are never leaked)
     */
    public static function getSafeItemMetadata(string $key): ?array
    {
        $item = static::where('item_key', $key)->first();
        if (! $item || ! $item->is_verified) {
            return null;
        }

        $meta = $item->metadata ?? [];
        // Ensure sensitive networking internal subnets are stripped if any
        unset($meta['subnet_cidrs'], $meta['routing_secrets'], $meta['private_key']);

        return $meta;
    }
}
