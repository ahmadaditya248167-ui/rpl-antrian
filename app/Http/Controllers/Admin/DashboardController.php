<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\Queue;
use App\Models\Service;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $today = today();

        $stats = [
            'total_queues'    => Queue::whereDate('appointment_date', $today)->count(),
            'waiting'         => Queue::whereDate('appointment_date', $today)->where('status', 'waiting')->count(),
            'serving'         => Queue::whereDate('appointment_date', $today)->where('status', 'serving')->count(),
            'done'            => Queue::whereDate('appointment_date', $today)->where('status', 'done')->count(),
            'skipped'         => Queue::whereDate('appointment_date', $today)->where('status', 'skipped')->count(),
            'active_services' => Service::where('is_active', true)->count(),
            'active_counters' => Counter::where('is_active', true)->count(),
            'total_officers'  => User::where('role', 'officer')->count(),
        ];

        $recentQueues = Queue::with(['service', 'counter', 'officer'])
            ->whereDate('appointment_date', $today)
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentQueues'));
    }
}
