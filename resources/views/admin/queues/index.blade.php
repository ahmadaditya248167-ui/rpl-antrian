@extends('layouts.admin')
@section('title', 'Monitor Antrian')
@section('page-title', 'Monitor Antrian')

@section('content')
<div id="queue-monitor">
    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.queues.index') }}" class="flex flex-wrap gap-2 mb-4" id="filter-form">
        <input type="date" name="date" value="{{ $date }}"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2">
        <select name="service_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2">
            <option value="">Semua Layanan</option>
            @foreach ($services as $service)
            <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                {{ $service->name }}
            </option>
            @endforeach
        </select>
        <select name="counter_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2">
            <option value="">Semua Loket</option>
            @foreach ($counters as $counter)
            <option value="{{ $counter->id }}" {{ request('counter_id') == $counter->id ? 'selected' : '' }}>
                {{ $counter->name }}
            </option>
            @endforeach
        </select>
        <select name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2">
            <option value="">Semua Status</option>
            <option value="waiting"  {{ request('status') === 'waiting'  ? 'selected' : '' }}>Menunggu</option>
            <option value="serving"  {{ request('status') === 'serving'  ? 'selected' : '' }}>Dilayani</option>
            <option value="done"     {{ request('status') === 'done'     ? 'selected' : '' }}>Selesai</option>
            <option value="skipped"  {{ request('status') === 'skipped'  ? 'selected' : '' }}>Dilewati</option>
        </select>
        <button type="submit" class="bg-gray-700 text-white text-sm rounded-lg px-4 py-2">Filter</button>
        <a href="{{ route('admin.queues.index') }}" class="bg-gray-100 text-gray-700 text-sm rounded-lg px-4 py-2">Reset</a>
    </form>

    {{-- Summary Badges --}}
    <div class="flex flex-wrap gap-3 mb-4 text-sm">
        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full font-medium">
            Menunggu: {{ $queues->where('status','waiting')->count() }}
        </span>
        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-medium">
            Dilayani: {{ $queues->where('status','serving')->count() }}
        </span>
        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full font-medium">
            Selesai: {{ $queues->where('status','done')->count() }}
        </span>
        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full font-medium">
            Dilewati: {{ $queues->where('status','skipped')->count() }}
        </span>
        <span id="refresh-indicator" class="ml-auto text-xs text-gray-400 self-center"></span>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">No. Antrian</th>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Layanan</th>
                        <th class="px-4 py-3">Loket</th>
                        <th class="px-4 py-3">Petugas</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Waktu Daftar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($queues as $queue)
                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-3 font-bold text-gray-900 dark:text-white text-base">
                            {{ $queue->service->prefix ?? '' }}{{ str_pad($queue->queue_number, 3, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-4 py-3">{{ $queue->visitor_name ?? $queue->user->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $queue->service->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $queue->counter->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $queue->officer->name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @php
                                $badge = match($queue->status) {
                                    'waiting' => 'bg-yellow-100 text-yellow-800',
                                    'serving' => 'bg-blue-100 text-blue-800',
                                    'done'    => 'bg-green-100 text-green-800',
                                    'skipped' => 'bg-red-100 text-red-800',
                                    default   => 'bg-gray-100 text-gray-800',
                                };
                                $label = match($queue->status) {
                                    'waiting' => 'Menunggu', 'serving' => 'Dilayani',
                                    'done'    => 'Selesai',  'skipped' => 'Dilewati',
                                    default   => $queue->status,
                                };
                            @endphp
                            <span class="text-xs font-medium px-2.5 py-0.5 rounded {{ $badge }}">{{ $label }}</span>
                        </td>
                        <td class="px-4 py-3">{{ $queue->created_at->format('H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400">Tidak ada antrian ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $queues->links() }}</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let countdown = 10;
    const indicator = document.getElementById('refresh-indicator');

    function tick() {
        if (indicator) indicator.textContent = `Auto-refresh dalam ${countdown}s`;
        countdown--;
        if (countdown < 0) {
            window.location.reload();
        } else {
            setTimeout(tick, 1000);
        }
    }

    // Only auto-refresh if viewing today
    const dateInput = document.querySelector('[name="date"]');
    const today = new Date().toISOString().slice(0, 10);
    if (!dateInput || dateInput.value === today) {
        tick();
    }
</script>
@endpush
