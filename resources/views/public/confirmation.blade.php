@extends('public.layout')
@section('title', 'Nomor Antrian Anda')

@section('content')
<div class="max-w-sm mx-auto text-center">

    {{-- Success Icon --}}
    <div class="mb-6">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h1 class="text-xl font-bold text-gray-800">Pendaftaran Berhasil!</h1>
        <p class="text-gray-500 text-sm mt-1">Simpan nomor antrian Anda di bawah ini</p>
    </div>

    {{-- Queue Ticket --}}
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8 mb-6">

        {{-- Dashed separator top --}}
        <div class="border-t-2 border-dashed border-gray-200 -mx-8 mb-6"></div>

        <p class="text-sm text-gray-500 uppercase tracking-widest mb-1">Nomor Antrian</p>
        <p class="text-7xl font-black font-mono text-blue-600 tracking-widest">{{ $data['code'] }}</p>

        <div class="border-t-2 border-dashed border-gray-200 -mx-8 mt-6 mb-6"></div>

        <div class="space-y-2 text-sm text-left">
            <div class="flex justify-between">
                <span class="text-gray-500">Nama</span>
                <span class="font-medium text-gray-800">{{ $data['visitor_name'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Layanan</span>
                <span class="font-medium text-gray-800">{{ $data['service_name'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Tanggal</span>
                <span class="font-medium text-gray-800">{{ $data['date'] }}</span>
            </div>
        </div>

        <div class="border-t-2 border-dashed border-gray-200 -mx-8 mt-6"></div>
    </div>

    {{-- Instructions --}}
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-sm text-yellow-800 text-left mb-6">
        <p class="font-semibold mb-1">Petunjuk:</p>
        <ul class="list-disc list-inside space-y-1 text-yellow-700">
            <li>Harap datang sebelum nomor antrian Anda dipanggil.</li>
            <li>Pantau layar display antrian di ruang tunggu.</li>
            <li>Tunjukkan nomor ini ke petugas saat dipanggil.</li>
        </ul>
    </div>

    <div class="flex flex-col gap-3">
        <a href="{{ route('public.display') }}"
            class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
            Lihat Display Antrian
        </a>
        <a href="{{ route('public.index') }}"
            class="text-gray-600 bg-gray-100 hover:bg-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
            Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
