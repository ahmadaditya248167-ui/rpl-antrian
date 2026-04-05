@extends('layouts.admin')
@section('title', 'Edit Pengguna')
@section('page-title', 'Edit Pengguna')

@section('content')
<div class="max-w-xl">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Nama</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5 @error('name') border-red-500 @enderror"
                    required>
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5 @error('email') border-red-500 @enderror"
                    required>
                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Password Baru <span class="text-gray-400 font-normal">(kosongkan jika tidak diubah)</span>
                </label>
                <input type="password" name="password"
                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5 @error('password') border-red-500 @enderror">
                @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation"
                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5">
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                <select name="role"
                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5">
                    <option value="officer" {{ old('role', $user->role) === 'officer' ? 'selected' : '' }}>Officer</option>
                    <option value="admin"   {{ old('role', $user->role) === 'admin'   ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">
                    Perbarui
                </button>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:underline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
