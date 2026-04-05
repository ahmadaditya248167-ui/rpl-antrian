<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\Queue;
use App\Models\Service;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::orderBy('name')->get();
        $counters = Counter::orderBy('name')->get();
        $date     = $request->get('date', today()->toDateString());

        $query = Queue::with(['service', 'counter', 'officer', 'user'])
            ->whereDate('appointment_date', $date);

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }
        if ($request->filled('counter_id')) {
            $query->where('counter_id', $request->counter_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $queues = $query->orderBy('queue_number')->paginate(25)->withQueryString();

        return view('admin.queues.index', compact('queues', 'services', 'counters', 'date'));
    }
}
