<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceSchedule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceScheduleController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::orderBy('name')->get();
        $query = ServiceSchedule::with('service');

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $schedules = $query->orderByDesc('date')->paginate(20)->withQueryString();
        return view('admin.schedules.index', compact('schedules', 'services'));
    }

    public function create()
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();
        return view('admin.schedules.create', compact('services'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'date'       => ['required', 'date'],
            'is_open'    => ['boolean'],
            'max_quota'  => ['required', 'integer', 'min:1'],
        ]);

        $exists = ServiceSchedule::where('service_id', $data['service_id'])
            ->whereDate('date', $data['date'])->exists();

        if ($exists) {
            return back()->withErrors(['date' => 'Jadwal untuk layanan dan tanggal ini sudah ada.'])->withInput();
        }

        $data['is_open'] = $request->boolean('is_open', true);
        ServiceSchedule::create($data);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(ServiceSchedule $schedule)
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();
        return view('admin.schedules.edit', compact('schedule', 'services'));
    }

    public function update(Request $request, ServiceSchedule $schedule)
    {
        $data = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'date'       => ['required', 'date'],
            'is_open'    => ['boolean'],
            'max_quota'  => ['required', 'integer', 'min:1'],
        ]);

        $exists = ServiceSchedule::where('service_id', $data['service_id'])
            ->whereDate('date', $data['date'])
            ->where('id', '!=', $schedule->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['date' => 'Jadwal untuk layanan dan tanggal ini sudah ada.'])->withInput();
        }

        $data['is_open'] = $request->boolean('is_open');
        $schedule->update($data);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(ServiceSchedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
