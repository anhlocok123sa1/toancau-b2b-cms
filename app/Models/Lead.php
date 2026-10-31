<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Lead extends Model
{
    use HasFactory, LogsActivity;

    public const STATUS_NEW = '1_new';

    public const STATUS_ACKNOWLEDGED = '2_acknowledged';

    public const STATUS_IN_PROGRESS = '3_in_progress';

    public const STATUS_QUOTED = '4_quoted';

    public const STATUS_NEGOTIATING = '5_negotiating';

    public const STATUS_CLOSED_WON = '6_closed_won';

    public const STATUS_CLOSED_LOST = '7_closed_lost';

    public const STATUS_LABELS = [
        self::STATUS_NEW => '[1] MỚI TIẾP NHẬN',
        self::STATUS_ACKNOWLEDGED => '[2] ĐÃ XÁC NHẬN SLA',
        self::STATUS_IN_PROGRESS => '[3] ĐANG TƯ VẤN & KHẢO SÁT',
        self::STATUS_QUOTED => '[4] ĐÃ GỬI BÁO GIÁ',
        self::STATUS_NEGOTIATING => '[5] ĐÀM PHÁN HỢP ĐỒNG',
        self::STATUS_CLOSED_WON => '[6] CHỐT HỢP ĐỒNG THÀNH CÔNG',
        '5_closed_won' => '[6] CHỐT HỢP ĐỒNG THÀNH CÔNG',
        self::STATUS_CLOSED_LOST => '[7] KHÔNG THÀNH CÔNG',
        '5_closed_lost' => '[7] KHÔNG THÀNH CÔNG',
    ];

    public const FORM_TYPES = [
        'consultation_quote' => 'Tư Vấn / Báo Giá',
        'partner_registration' => 'Đăng Ký Đối Tác / Đại Lý',
        'technical_service' => 'Yêu Cầu Dịch Vụ Kỹ Thuật',
    ];

    protected $fillable = [
        'form_type',
        'code',
        'full_name',
        'company_name',
        'phone',
        'email',
        'city_province',
        'interested_service',
        'product_id',
        'message',
        'attachment_path',
        'attachment_name',
        'data_consent',
        'status',
        'sla_due_at',
        'assigned_to',
        'closed_reason',
        'contract_number',
        'contract_value',
        'contract_term',
        'contract_payment_terms',
        'contract_signed_at',
        'contract_notes',
        'contract_attachment_path',
        'contract_attachment_name',
        'notes',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'data_consent' => 'boolean',
            'sla_due_at' => 'datetime',
            'contract_signed_at' => 'date',
            'contract_value' => 'decimal:2',
            'notes' => 'array',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['code', 'status', 'assigned_to', 'closed_reason'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Check whether lead is currently breaching its 30-minute SLA
     */
    public function isSlaBreached(): bool
    {
        if (in_array($this->status, [self::STATUS_CLOSED_WON, self::STATUS_CLOSED_LOST], true)) {
            return false;
        }

        return now()->isAfter($this->sla_due_at);
    }

    /**
     * Append a note/audit step to the internal notes array
     */
    public function appendTimelineNote(string $actorName, string $text): void
    {
        $currentNotes = $this->notes ?? [];
        $currentNotes[] = [
            'time' => now()->toIso8601String(),
            'actor' => $actorName,
            'text' => $text,
        ];
        $this->notes = $currentNotes;
        $this->save();
    }
}
