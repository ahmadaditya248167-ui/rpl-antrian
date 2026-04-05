<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use App\Models\Service;
use App\Models\ServiceSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueueRegistrationController extends Controller
{
    /**
     * Show all services available today.
     */
    public function index()
    {
        $services = Service::where('is_active', true)
            ->with(['todaySchedule', 'queues' => function ($q) {
                $q->whereDate('appointment_date', today())
                  ->whereNotIn('status', ['skipped']);
            }])
            ->get()
            ->map(function ($service) {
                $schedule = $service->todaySchedule;
                $booked   = $service->queues->count();

                $service->schedule_open   = $schedule && $schedule->is_open;
                $service->max_quota       = $schedule->max_quota ?? 0;
                $service->booked_count    = $booked;
                $service->remaining_quota = $schedule ? max(0, $schedule->max_quota - $booked) : 0;
                $service->can_register    = $schedule && $schedule->is_open && $service->remaining_quota > 0;

                return $service;
            });

        return view('public.index', compact('services'));
    }

    /**
     * Show registration form for a specific service.
     */
    public function show(Service $service)
    {
        $schedule = ServiceSchedule::where('service_id', $service->id)
            ->whereDate('date', today())
            ->first();

        if (!$schedule || !$schedule->is_open) {
            return redirect()->route('public.index')
                ->with('error', 'Layanan ini tidak tersedia hari ini.');
        }

        $booked    = Queue::where('service_id', $service->id)
            ->whereDate('appointment_date', today())
            ->whereNotIn('status', ['skipped'])
            ->count();

        $remaining = max(0, $schedule->max_quota - $booked);

        if ($remaining <= 0) {
            return redirect()->route('public.index')
                ->with('error', 'Kuota layanan ' . $service->name . ' hari ini sudah penuh.');
        }

        return view('public.register', compact('service', 'schedule', 'remaining'));
    }

    /**
     * Process queue registration.
     */
    public function store(Request $request, Service $service)
    {
        $validated = $request->validate([
            'visitor_name'  => ['required', 'string', 'max:255'],
            'visitor_phone' => ['nullable', 'string', 'max:20'],
        ]);

        // Lock to prevent race conditions on quota
        $queue = DB::transaction(function () use ($validated, $service) {
            $schedule = ServiceSchedule::where('service_id', $service->id)
                ->whereDate('date', today())
                ->lockForUpdate()
                ->first();

            if (!$schedule || !$schedule->is_open) {
                return null;
            }

            $booked = Queue::where('service_id', $service->id)
                ->whereDate('appointment_date', today())
                ->whereNotIn('status', ['skipped'])
                ->count();

            if ($booked >= $schedule->max_quota) {
                return null;
            }

            $nextNumber = Queue::where('service_id', $service->id)
                ->whereDate('appointment_date', today())
                ->max('queue_number') + 1;

            return Queue::create([
                'service_id'       => $service->id,
                'visitor_name'     => $validated['visitor_name'],
                'visitor_phone'    => $validated['visitor_phone'] ?? null,
                'queue_number'     => $nextNumber,
                'appointment_date' => today(),
                'status'           => 'waiting',
            ]);
        });

        if (!$queue) {
            return redirect()->route('public.register', $service->slug)
                ->with('error', 'Maaf, kuota sudah penuh atau layanan tidak tersedia.');
        }

        // Store confirmation in session
        $request->session()->put('last_queue', [
            'code'         => $service->prefix . str_pad($queue->queue_number, 3, '0', STR_PAD_LEFT),
            'service_name' => $service->name,
            'visitor_name' => $queue->visitor_name,
            'date'         => today()->isoFormat('dddd, D MMMM Y'),
            'queue_id'     => $queue->id,
        ]);

        return redirect()->route('public.confirmation');
    }

    /**
     * Show confirmation slip.
     */
    public function confirmation(Request $request)
    {
        $data = $request->session()->get('last_queue');

        if (!$data) {
            return redirect()->route('public.index');
        }

        return view('public.confirmation', compact('data'));
    }
}
