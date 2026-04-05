<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Antrian') — Layanan Publik</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen">

{{-- Top Nav --}}
<nav class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-4xl mx-auto px-4 py-4 flex items-center justify-between">
        <a href="{{ route('public.index') }}" class="flex items-center gap-2">
            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span class="text-lg font-bold text-gray-800">Antrian Online</span>
        </a>
        <div class="flex items-center gap-4 text-sm">
            <a href="{{ route('public.index') }}"
                class="text-gray-600 hover:text-blue-600 font-medium {{ request()->routeIs('public.index') ? 'text-blue-600' : '' }}">
                Ambil Nomor
            </a>
            <a href="{{ route('public.display') }}"
                class="text-gray-600 hover:text-blue-600 font-medium {{ request()->routeIs('public.display') ? 'text-blue-600' : '' }}">
                Display Antrian
            </a>
            <a href="{{ route('login') }}" class="text-gray-400 hover:text-gray-600">Petugas</a>
        </div>
    </div>
</nav>

{{-- Main Content --}}
<main class="max-w-4xl mx-auto px-4 py-8">

    @if (session('error'))
        <div class="mb-6 p-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    @yield('content')

</main>

{{-- Footer --}}
<footer class="text-center text-xs text-gray-400 py-8">
    {{ today()->isoformat('dddd, D MMMM Y') }}
</footer>

@stack('scripts')
</body>
</html>
