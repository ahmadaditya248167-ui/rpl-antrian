@extends('layouts.officer')
@section('title', 'Dashboard Petugas')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Currently Serving --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
        <h3 class="text-base font-semibold text-gray-700 dark:text-white mb-4">Sedang Dilayani</h3>

        @if ($servingQueue)
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg border border-blue-200">
            <div class="text-center sm:text-left">
                <p class="text-5xl font-black text-blue-700 dark:text-blue-300 tracking-widest">
                    {{ $servingQueue->service->prefix }}{{ str_pad($servingQueue->queue_number, 3, '0', STR_PAD_LEFT) }}
                </p>
                <p class="text-sm text-gray-500 mt-1">{{ $servingQueue->service->name }}</p>
                @if ($servingQueue->user)
                <p class="text-sm text-gray-600 mt-0.5">{{ $servingQueue->visitor_name ?? $servingQueue->user->name ?? '—' }}</p>
                @endif
            </div>
            <div class="flex gap-2">
                <form method="POST" action="{{ route('officer.queue.done', $servingQueue) }}">
                    @csrf @method('PATCH')
                    <button type="submit"
                        class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5">
                        ✓ Selesai
                    </button>
                </form>
                <form method="POST" action="{{ route('officer.queue.skip', $servingQueue) }}"
                    onsubmit="return confirm('Lewati antrian ini?')">
                    @csrf @method('PATCH')
                    <button type="submit"
                        class="text-white bg-red-500 hover:bg-red-600 font-medium rounded-lg text-sm px-5 py-2.5">
                        ✗ Lewati
                    </button>
                </form>
            </div>
        </div>
        @else
        <div class="text-center py-6">
            <p class="text-gray-400 mb-4">Tidak ada antrian yang sedang dilayani.</p>
            <form method="POST" action="{{ route('officer.queue.call-next') }}">
                @csrf
                <button type="submit"
                    class="text-white bg-blue-600 hover:bg-blue-700 font-semibold rounded-lg px-8 py-3">
                    Panggil Antrian Berikutnya
                </button>
            </form>
        </div>
        @endif
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <p class="text-sm text-gray-500">Menunggu Hari Ini</p>
            <p class="text-3xl font-bold text-yellow-500">{{ $waitingQueues->count() }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <p class="text-sm text-gray-500">Selesai Dilayani</p>
            <p class="text-3xl font-bold text-green-600">{{ $todayDone }}</p>
        </div>
    </div>

    {{-- Waiting Queue List --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-base font-semibold text-gray-700 dark:text-white">Antrian Menunggu</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3">No. Antrian</th>
                        <th class="px-4 py-3">Layanan</th>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Waktu Daftar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($waitingQueues as $queue)
                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-3 font-bold text-gray-900 dark:text-white">
                            {{ $queue->service->prefix }}{{ str_pad($queue->queue_number, 3, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-4 py-3">{{ $queue->service->name }}</td>
                        <td class="px-4 py-3">{{ $queue->visitor_name ?? $queue->user->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $queue->created_at->format('H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-4 py-6 text-center text-gray-400">Tidak ada antrian menunggu.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Auto-refresh every 15 seconds
    let sec = 15;
    function tick() {
        sec--;
        if (sec <= 0) window.location.reload();
        else setTimeout(tick, 1000);
    }
    tick();
</script>
@endpush
