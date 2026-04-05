<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\Queue;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function selectCounter()
    {
        $counters = Counter::where('is_active', true)->with('services')->orderBy('name')->get();
        return view('officer.counter-select', compact('counters'));
    }

    public function storeCounter(Request $request)
    {
        $request->validate([
            'counter_id' => ['required', 'exists:counters,id'],
        ]);

        $counter = Counter::findOrFail($request->counter_id);
        $request->session()->put('counter_id', $counter->id);
        $request->session()->put('counter_name', $counter->name);

        return redirect()->route('officer.dashboard');
    }

    public function index(Request $request)
    {
        if (!$request->session()->has('counter_id')) {
            return redirect()->route('officer.counter.select');
        }

        $counterId = $request->session()->get('counter_id');
        $counter   = Counter::with('services')->findOrFail($counterId);
        $serviceIds = $counter->services->pluck('id');

        $waitingQueues = Queue::with(['service', 'user'])
            ->whereDate('appointment_date', today())
            ->where('status', 'waiting')
            ->whereIn('service_id', $serviceIds)
            ->orderBy('queue_number')
            ->get();

        $servingQueue = Queue::with(['service', 'user'])
            ->whereDate('appointment_date', today())
            ->where('status', 'serving')
            ->where('counter_id', $counterId)
            ->first();

        $todayDone = Queue::whereDate('appointment_date', today())
            ->where('counter_id', $counterId)
            ->where('status', 'done')
            ->count();

        return view('officer.dashboard', compact('counter', 'waitingQueues', 'servingQueue', 'todayDone'));
    }
}
