<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyQuota extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'is_closed' => 'boolean',
        'max_quota' => 'integer',
        'booked_count' => 'integer',
    ];

    public function getRemainingQuotaAttribute(): int
    {
        return max(0, $this->max_quota - $this->booked_count);
    }

    public function getIsFullAttribute(): bool
    {
        return $this->is_closed || $this->remaining_quota <= 0;
    }

    public static function getStatusForDate(string $dateString): array
    {
        $defaultMax = (int) StoreSetting::get('default_daily_quota', 15);
        $record = self::where('date', $dateString)->first();

        if (! $record) {
            return [
                'date' => $dateString,
                'is_available' => true,
                'is_closed' => false,
                'is_full' => false,
                'max_quota' => $defaultMax,
                'booked_count' => 0,
                'remaining' => $defaultMax,
                'note' => null,
            ];
        }

        $isClosed = $record->is_closed;
        $isFull = $isClosed || ($record->remaining_quota <= 0);

        return [
            'date' => $dateString,
            'is_available' => ! $isFull,
            'is_closed' => $isClosed,
            'is_full' => $isFull,
            'max_quota' => $record->max_quota,
            'booked_count' => $record->booked_count,
            'remaining' => $record->remaining_quota,
            'note' => $record->close_reason,
        ];
    }
}
