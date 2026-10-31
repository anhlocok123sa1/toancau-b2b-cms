<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class VpsPlan extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'slug',
        'vcpu',
        'ram_gb',
        'storage_nvme_gb',
        'bandwidth',
        'port_speed',
        'ipv4_count',
        'price_monthly',
        'price_yearly',
        'discount_percent_yearly',
        'features',
        'is_popular',
        'display_order',
        'phase2_template_id',
    ];

    protected function casts(): array
    {
        return [
            'vcpu' => 'integer',
            'ram_gb' => 'integer',
            'storage_nvme_gb' => 'integer',
            'ipv4_count' => 'integer',
            'price_monthly' => 'decimal:2',
            'price_yearly' => 'decimal:2',
            'discount_percent_yearly' => 'integer',
            'features' => 'array',
            'is_popular' => 'boolean',
            'display_order' => 'integer',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'price_monthly', 'price_yearly', 'is_popular'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
