@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stat Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow flex items-center gap-4">
        <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Antrian Hari Ini</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['total_queues'] }}</p>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow flex items-center gap-4">
        <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Menunggu</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['waiting'] }}</p>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow flex items-center gap-4">
        <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Selesai</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['done'] }}</p>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow flex items-center gap-4">
        <div class="p-3 rounded-full bg-red-100 dark:bg-red-900">
            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Dilewati</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['skipped'] }}</p>
        </div>
    </div>
</div>

{{-- Secondary Stats --}}
<div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow">
        <p class="text-sm text-gray-500 dark:text-gray-400">Layanan Aktif</p>
        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['active_services'] }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow">
        <p class="text-sm text-gray-500 dark:text-gray-400">Loket Aktif</p>
        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['active_counters'] }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow">
        <p class="text-sm text-gray-500 dark:text-gray-400">Petugas</p>
        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['total_officers'] }}</p>
    </div>
</div>

{{-- Recent Queues --}}
<div class="bg-white dark:bg-gray-800 rounded-lg shadow">
    <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
        <h3 class="text-base font-semibold text-gray-800 dark:text-white">Antrian Terbaru Hari Ini</h3>
        <a href="{{ route('admin.queues.index') }}" class="text-sm text-blue-600 hover:underline">Lihat semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">No. Antrian</th>
                    <th class="px-4 py-3">Layanan</th>
                    <th class="px-4 py-3">Loket</th>
                    <th class="px-4 py-3">Petugas</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentQueues as $queue)
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                        {{ $queue->service->prefix ?? '-' }}{{ str_pad($queue->queue_number, 3, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-4 py-3">{{ $queue->service->name ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $queue->counter->name ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $queue->officer->name ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @php
                            $badge = match($queue->status) {
                                'waiting'  => 'bg-yellow-100 text-yellow-800',
                                'serving'  => 'bg-blue-100 text-blue-800',
                                'done'     => 'bg-green-100 text-green-800',
                                'skipped'  => 'bg-red-100 text-red-800',
                                default    => 'bg-gray-100 text-gray-800',
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
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-400">Belum ada antrian hari ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
