<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PageSection extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'page_key',
        'section_key',
        'title',
        'subtitle',
        'badge',
        'content',
        'metadata',
        'display_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'is_active' => 'boolean',
            'display_order' => 'integer',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['page_key', 'section_key', 'title', 'content', 'metadata', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Helper to retrieve section for a given page and key
     */
    public static function getSection(string $pageKey, string $sectionKey, $default = null): ?self
    {
        return static::where('page_key', $pageKey)
            ->where('section_key', $sectionKey)
            ->first() ?? $default;
    }

    /**
     * Helper to retrieve all sections for a page indexed by section_key
     */
    public static function getPageSections(string $pageKey): array
    {
        return static::where('page_key', $pageKey)
            ->where('is_active', true)
            ->orderBy('display_order')
            ->get()
            ->keyBy('section_key')
            ->toArray();
    }
}
