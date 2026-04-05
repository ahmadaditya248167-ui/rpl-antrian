<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceSchedule extends Model
{
    protected $fillable = [
        'service_id',
        'date',
        'is_open',
        'max_quota',
    ];

    protected $casts = [
        'date'    => 'date',
        'is_open' => 'boolean',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getBookedCountAttribute(): int
    {
        return Queue::where('service_id', $this->service_id)
            ->whereDate('appointment_date', $this->date)
            ->whereNotIn('status', ['skipped'])
            ->count();
    }
}
