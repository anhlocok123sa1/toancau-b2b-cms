<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'category_id',
        'sector_code',
        'name',
        'slug',
        'model_code',
        'badge',
        'thumbnail',
        'gallery',
        'overview',
        'features',
        'specs',
        'application_scenarios',
        'datasheet_pdf_path',
        'warranty_policy',
        'support_commitment',
        'related_product_ids',
        'is_price_visible',
        'price',
        'price_unit',
        'is_featured',
        'is_active',
        'meta_title',
        'meta_description',
        'provisioning_driver',
        'provisioning_metadata',
    ];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'features' => 'array',
            'specs' => 'array',
            'application_scenarios' => 'array',
            'related_product_ids' => 'array',
            'provisioning_metadata' => 'array',
            'is_price_visible' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'price' => 'decimal:2',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'model_code', 'is_active', 'is_price_visible', 'price'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Retrieve related cross-selling products
     */
    public function getRelatedProductsAttribute()
    {
        if (empty($this->related_product_ids)) {
            return Product::where('id', '!=', $this->id)
                ->where('sector_code', $this->sector_code)
                ->where('is_active', true)
                ->limit(4)
                ->get(['id', 'name', 'slug', 'model_code', 'thumbnail', 'is_price_visible', 'price', 'price_unit', 'overview']);
        }

        return Product::whereIn('id', $this->related_product_ids)
            ->where('is_active', true)
            ->get(['id', 'name', 'slug', 'model_code', 'thumbnail', 'is_price_visible', 'price', 'price_unit', 'overview']);
    }

    /**
     * Phase 2 Hook: Check if product supports automated cloud provisioning
     */
    public function canAutoProvision(): bool
    {
        return in_array($this->provisioning_driver, ['proxmox', 'openstack', 'ipam'], true);
    }
}
