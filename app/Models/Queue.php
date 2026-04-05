<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $fillable = [
        'user_id',
        'service_id',
        'counter_id',
        'served_by',
        'visitor_name',
        'visitor_phone',
        'queue_number',
        'appointment_date',
        'status',
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function counter()
    {
        return $this->belongsTo(Counter::class);
    }

    public function officer()
    {
        return $this->belongsTo(User::class, 'served_by');
    }

    public function getQueueCodeAttribute(): string
    {
        return $this->service->prefix . str_pad($this->queue_number, 3, '0', STR_PAD_LEFT);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', today());
    }

    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }
}
