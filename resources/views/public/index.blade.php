@extends('public.layout')
@section('title', 'Ambil Nomor Antrian')

@section('content')
<div class="text-center mb-8">
    <h1 class="text-2xl font-bold text-gray-800">Ambil Nomor Antrian</h1>
    <p class="text-gray-500 mt-1">Pilih layanan yang Anda butuhkan hari ini</p>
</div>

<div class="grid gap-4 sm:grid-cols-2">
    @forelse ($services as $service)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col gap-3
        {{ !$service->can_register ? 'opacity-60' : '' }}">

        <div class="flex items-start justify-between">
            <div>
                <span class="inline-block font-mono font-black text-2xl text-blue-600 mr-2">{{ $service->prefix }}</span>
                <span class="font-semibold text-gray-800 text-lg">{{ $service->name }}</span>
            </div>
            @if ($service->can_register)
                <span class="text-xs font-medium bg-green-100 text-green-700 px-2.5 py-1 rounded-full">Tersedia</span>
            @elseif (!$service->schedule_open)
                <span class="text-xs font-medium bg-gray-100 text-gray-500 px-2.5 py-1 rounded-full">Tutup</span>
            @else
                <span class="text-xs font-medium bg-red-100 text-red-700 px-2.5 py-1 rounded-full">Penuh</span>
            @endif
        </div>

        @if ($service->description)
            <p class="text-sm text-gray-500">{{ $service->description }}</p>
        @endif

        <div class="flex items-center gap-4 text-sm text-gray-500">
            <span>Sisa kuota: <strong class="text-gray-700">{{ $service->remaining_quota }}</strong></span>
            <span>Terdaftar: <strong class="text-gray-700">{{ $service->booked_count }}</strong></span>
        </div>

        @if ($service->can_register)
            <a href="{{ route('public.register', $service->slug) }}"
                class="mt-1 inline-block text-center text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                Ambil Nomor
            </a>
        @else
            <button disabled
                class="mt-1 inline-block text-center text-white bg-gray-400 font-medium rounded-lg text-sm px-5 py-2.5 cursor-not-allowed">
                Tidak Tersedia
            </button>
        @endif
    </div>
    @empty
    <div class="col-span-2 text-center py-12 text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
        </svg>
        <p>Tidak ada layanan tersedia hari ini.</p>
    </div>
    @endforelse
</div>
@endsection
