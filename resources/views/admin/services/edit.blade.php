@extends('layouts.admin')
@section('title', 'Edit Layanan')
@section('page-title', 'Edit Layanan')

@section('content')
<div class="max-w-xl">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.services.update', $service) }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Nama Layanan</label>
                <input type="text" name="name" value="{{ old('name', $service->name) }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5 @error('name') border-red-500 @enderror"
                    required>
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Prefix</label>
                <input type="text" name="prefix" value="{{ old('prefix', $service->prefix) }}" maxlength="5"
                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5 @error('prefix') border-red-500 @enderror"
                    required>
                @error('prefix') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                <textarea name="description" rows="3"
                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5">{{ old('description', $service->description) }}</textarea>
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                    class="w-4 h-4 text-blue-600 border-gray-300 rounded"
                    {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">Aktifkan layanan</label>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Perbarui</button>
                <a href="{{ route('admin.services.index') }}" class="text-sm text-gray-500 hover:underline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
