@extends('layouts.admin')
@section('title', 'Tambah Loket')
@section('page-title', 'Tambah Loket')

@section('content')
<div class="max-w-xl">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.counters.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Nama Loket</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5 @error('name') border-red-500 @enderror"
                    required>
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Layanan yang Dilayani
                </label>
                <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-3 bg-gray-50">
                    @foreach ($services as $service)
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="service_ids[]" id="svc_{{ $service->id }}" value="{{ $service->id }}"
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded"
                            {{ in_array($service->id, old('service_ids', [])) ? 'checked' : '' }}>
                        <label for="svc_{{ $service->id }}" class="text-sm text-gray-700">
                            <span class="font-mono font-bold text-blue-600">{{ $service->prefix }}</span> — {{ $service->name }}
                        </label>
                    </div>
                    @endforeach
                </div>
                @error('service_ids') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                    class="w-4 h-4 text-blue-600 border-gray-300 rounded"
                    {{ old('is_active', '1') ? 'checked' : '' }}>
                <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">Aktifkan loket</label>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Simpan</button>
                <a href="{{ route('admin.counters.index') }}" class="text-sm text-gray-500 hover:underline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
