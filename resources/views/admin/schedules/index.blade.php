@extends('layouts.admin')
@section('title', 'Jadwal Layanan')
@section('page-title', 'Jadwal Layanan')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
    {{-- Filter --}}
    <form method="GET" action="{{ route('admin.schedules.index') }}" class="flex flex-wrap gap-2">
        <select name="service_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2">
            <option value="">Semua Layanan</option>
            @foreach ($services as $service)
            <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                {{ $service->name }}
            </option>
            @endforeach
        </select>
        <input type="date" name="date" value="{{ request('date') }}"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2">
        <button type="submit" class="bg-gray-700 text-white text-sm rounded-lg px-4 py-2">Filter</button>
        <a href="{{ route('admin.schedules.index') }}" class="bg-gray-100 text-gray-700 text-sm rounded-lg px-4 py-2">Reset</a>
    </form>
    <a href="{{ route('admin.schedules.create') }}"
        class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 py-2 flex items-center gap-2 whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Tambah Jadwal
    </a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Layanan</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Kuota</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($schedules as $schedule)
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                        {{ $schedule->date->format('d M Y') }}
                        @if($schedule->date->isToday())
                            <span class="ml-1 text-xs bg-blue-100 text-blue-800 px-1.5 py-0.5 rounded">Hari ini</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $schedule->service->name ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs font-medium px-2.5 py-0.5 rounded {{ $schedule->is_open ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $schedule->is_open ? 'Buka' : 'Tutup' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">{{ $schedule->max_quota }}</td>
                    <td class="px-4 py-3 flex items-center gap-2">
                        <a href="{{ route('admin.schedules.edit', $schedule) }}" class="font-medium text-blue-600 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}"
                            onsubmit="return confirm('Hapus jadwal ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="font-medium text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">Belum ada jadwal.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ $schedules->links() }}</div>
</div>
@endsection
