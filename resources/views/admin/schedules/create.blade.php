@extends('layouts.admin')
@section('title', 'Tambah Jadwal')
@section('page-title', 'Tambah Jadwal Layanan')

@section('content')
<div class="max-w-lg">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.schedules.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Layanan</label>
                <select name="service_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5 @error('service_id') border-red-500 @enderror"
                    required>
                    <option value="">— Pilih Layanan —</option>
                    @foreach ($services as $service)
                    <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                        {{ $service->name }}
                    </option>
                    @endforeach
                </select>
                @error('service_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal</label>
                <input type="date" name="date" value="{{ old('date') }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5 @error('date') border-red-500 @enderror"
                    required>
                @error('date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Kuota Maksimal</label>
                <input type="number" name="max_quota" value="{{ old('max_quota', 50) }}" min="1"
                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5 @error('max_quota') border-red-500 @enderror"
                    required>
                @error('max_quota') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_open" id="is_open" value="1"
                    class="w-4 h-4 text-blue-600 border-gray-300 rounded"
                    {{ old('is_open', '1') ? 'checked' : '' }}>
                <label for="is_open" class="text-sm font-medium text-gray-700 dark:text-gray-300">Layanan dibuka pada tanggal ini</label>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Simpan</button>
                <a href="{{ route('admin.schedules.index') }}" class="text-sm text-gray-500 hover:underline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
