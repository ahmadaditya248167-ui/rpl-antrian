<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\Service;
use Illuminate\Http\Request;

class CounterController extends Controller
{
    public function index()
    {
        $counters = Counter::with('services')->latest()->paginate(15);
        return view('admin.counters.index', compact('counters'));
    }

    public function create()
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();
        return view('admin.counters.create', compact('services'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'is_active'   => ['boolean'],
            'service_ids' => ['nullable', 'array'],
            'service_ids.*' => ['exists:services,id'],
        ]);

        $counter = Counter::create([
            'name'      => $data['name'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        if (!empty($data['service_ids'])) {
            $counter->services()->sync($data['service_ids']);
        }

        return redirect()->route('admin.counters.index')->with('success', 'Loket berhasil ditambahkan.');
    }

    public function edit(Counter $counter)
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();
        $selectedServiceIds = $counter->services->pluck('id')->toArray();
        return view('admin.counters.edit', compact('counter', 'services', 'selectedServiceIds'));
    }

    public function update(Request $request, Counter $counter)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'is_active'     => ['boolean'],
            'service_ids'   => ['nullable', 'array'],
            'service_ids.*' => ['exists:services,id'],
        ]);

        $counter->update([
            'name'      => $data['name'],
            'is_active' => $request->boolean('is_active'),
        ]);

        $counter->services()->sync($data['service_ids'] ?? []);

        return redirect()->route('admin.counters.index')->with('success', 'Loket berhasil diperbarui.');
    }

    public function destroy(Counter $counter)
    {
        $counter->delete();
        return redirect()->route('admin.counters.index')->with('success', 'Loket berhasil dihapus.');
    }
}
