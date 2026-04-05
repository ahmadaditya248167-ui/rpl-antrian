<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\Queue;
use App\Models\Service;

class DisplayController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)
            ->with(['todaySchedule'])
            ->get()
            ->map(function ($service) {
                $service->now_serving = Queue::where('service_id', $service->id)
                    ->whereDate('appointment_date', today())
                    ->where('status', 'serving')
                    ->with('counter')
                    ->get();

                $service->waiting_count = Queue::where('service_id', $service->id)
                    ->whereDate('appointment_date', today())
                    ->where('status', 'waiting')
                    ->count();

                $service->done_count = Queue::where('service_id', $service->id)
                    ->whereDate('appointment_date', today())
                    ->where('status', 'done')
                    ->count();

                $schedule = $service->todaySchedule;
                $booked   = Queue::where('service_id', $service->id)
                    ->whereDate('appointment_date', today())
                    ->whereNotIn('status', ['skipped'])
                    ->count();

                $service->remaining_quota = $schedule
                    ? max(0, $schedule->max_quota - $booked)
                    : 0;
                $service->is_open_today = $schedule && $schedule->is_open;

                return $service;
            });

        return view('public.display', compact('services'));
    }
}
