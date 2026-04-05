@extends('layouts.admin')
@section('title', 'Layanan')
@section('page-title', 'Manajemen Layanan')

@section('content')
<div class="flex justify-between items-center mb-4">
    <p class="text-sm text-gray-500">Total: {{ $services->total() }} layanan</p>
    <a href="{{ route('admin.services.create') }}"
        class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 py-2 flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Tambah Layanan
    </a>
</div>
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Prefix</th>
                    <th class="px-4 py-3">Slug</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($services as $service)
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $service->name }}</td>
                    <td class="px-4 py-3 font-mono font-bold text-blue-600">{{ $service->prefix }}</td>
                    <td class="px-4 py-3 text-gray-400">{{ $service->slug }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs font-medium px-2.5 py-0.5 rounded {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $service->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 flex items-center gap-2">
                        <a href="{{ route('admin.services.edit', $service) }}" class="font-medium text-blue-600 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('admin.services.destroy', $service) }}"
                            onsubmit="return confirm('Hapus layanan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="font-medium text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">Belum ada layanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ $services->links() }}</div>
</div>
@endsection
