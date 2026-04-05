<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'prefix',
        'slug',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function counters()
    {
        return $this->belongsToMany(Counter::class, 'counter_service');
    }

    public function schedules()
    {
        return $this->hasMany(ServiceSchedule::class);
    }

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }

    public function todaySchedule()
    {
        return $this->hasOne(ServiceSchedule::class)->whereDate('date', today());
    }
}
