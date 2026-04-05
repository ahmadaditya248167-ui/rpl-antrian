@extends('layouts.admin')
@section('title', 'Loket')
@section('page-title', 'Manajemen Loket')

@section('content')
<div class="flex justify-between items-center mb-4">
    <p class="text-sm text-gray-500">Total: {{ $counters->total() }} loket</p>
    <a href="{{ route('admin.counters.create') }}"
        class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 py-2 flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Tambah Loket
    </a>
</div>
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">Nama Loket</th>
                    <th class="px-4 py-3">Layanan</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($counters as $counter)
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $counter->name }}</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-1">
                            @forelse ($counter->services as $service)
                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded">{{ $service->prefix }}</span>
                            @empty
                                <span class="text-gray-400 text-xs">—</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-xs font-medium px-2.5 py-0.5 rounded {{ $counter->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $counter->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 flex items-center gap-2">
                        <a href="{{ route('admin.counters.edit', $counter) }}" class="font-medium text-blue-600 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('admin.counters.destroy', $counter) }}"
                            onsubmit="return confirm('Hapus loket ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="font-medium text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-6 text-center text-gray-400">Belum ada loket.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ $counters->links() }}</div>
</div>
@endsection
