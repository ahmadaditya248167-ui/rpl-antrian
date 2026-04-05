@extends('public.layout')
@section('title', 'Daftar Antrian — ' . $service->name)

@section('content')
<div class="max-w-md mx-auto">

    {{-- Back --}}
    <a href="{{ route('public.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-blue-600 mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Kembali
    </a>

    {{-- Service Info --}}
    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6 flex items-center gap-4">
        <span class="text-4xl font-black font-mono text-blue-600">{{ $service->prefix }}</span>
        <div>
            <p class="font-semibold text-gray-800">{{ $service->name }}</p>
            @if ($service->description)
                <p class="text-sm text-gray-500 mt-0.5">{{ $service->description }}</p>
            @endif
            <p class="text-sm text-gray-500 mt-1">
                Sisa kuota: <strong class="text-blue-700">{{ $remaining }}</strong>
            </p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Isi Data Anda</h2>

        <form method="POST" action="{{ route('public.register.store', $service->slug) }}" class="space-y-4">
            @csrf

            <div>
                <label class="block mb-1.5 text-sm font-medium text-gray-700">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" name="visitor_name" value="{{ old('visitor_name') }}"
                    placeholder="Masukkan nama lengkap Anda"
                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5 focus:ring-blue-500 focus:border-blue-500 @error('visitor_name') border-red-500 @enderror"
                    required autofocus>
                @error('visitor_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-1.5 text-sm font-medium text-gray-700">
                    No. HP <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <input type="tel" name="visitor_phone" value="{{ old('visitor_phone') }}"
                    placeholder="08xxxxxxxxxx"
                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <button type="submit"
                class="w-full text-white bg-blue-600 hover:bg-blue-700 font-semibold rounded-lg text-sm px-5 py-3 mt-2 transition-colors">
                Ambil Nomor Antrian
            </button>
        </form>
    </div>
</div>
@endsection
