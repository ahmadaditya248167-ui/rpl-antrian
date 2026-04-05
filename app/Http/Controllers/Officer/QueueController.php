<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    public function callNext(Request $request)
    {
        $counterId  = $request->session()->get('counter_id');

        if (!$counterId) {
            return back()->with('error', 'Pilih loket terlebih dahulu.');
        }

        // Check no queue is currently being served at this counter
        $alreadyServing = Queue::where('counter_id', $counterId)
            ->where('status', 'serving')
            ->whereDate('appointment_date', today())
            ->exists();

        if ($alreadyServing) {
            return back()->with('error', 'Masih ada antrian yang sedang dilayani di loket ini.');
        }

        // Get the counter's service IDs
        $counter    = \App\Models\Counter::with('services')->findOrFail($counterId);
        $serviceIds = $counter->services->pluck('id');

        $next = Queue::whereDate('appointment_date', today())
            ->where('status', 'waiting')
            ->whereIn('service_id', $serviceIds)
            ->orderBy('queue_number')
            ->first();

        if (!$next) {
            return back()->with('error', 'Tidak ada antrian yang menunggu.');
        }

        $next->update([
            'status'     => 'serving',
            'counter_id' => $counterId,
            'served_by'  => auth()->id(),
        ]);

        return back()->with('success', "Memanggil nomor antrian {$next->service->prefix}" . str_pad($next->queue_number, 3, '0', STR_PAD_LEFT));
    }

    public function done(Request $request, Queue $queue)
    {
        $this->authorizeQueueAction($request, $queue);
        $queue->update(['status' => 'done']);
        return back()->with('success', 'Antrian selesai dilayani.');
    }

    public function skip(Request $request, Queue $queue)
    {
        $this->authorizeQueueAction($request, $queue);
        $queue->update(['status' => 'skipped', 'counter_id' => null, 'served_by' => null]);
        return back()->with('success', 'Antrian dilewati.');
    }

    protected function authorizeQueueAction(Request $request, Queue $queue): void
    {
        $counterId = $request->session()->get('counter_id');
        if ($queue->counter_id !== (int) $counterId || $queue->status !== 'serving') {
            abort(403);
        }
    }
}
