<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Petugas') — Antrian</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-100 dark:bg-gray-900">

<nav class="bg-gray-800 border-b border-gray-700 px-4 py-3 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <span class="text-white font-semibold">Antrian — Petugas</span>
        @if (session('counter_name'))
        <span class="text-xs bg-blue-600 text-white px-2 py-0.5 rounded-full">{{ session('counter_name') }}</span>
        @endif
    </div>
    <div class="flex items-center gap-4">
        <span class="text-sm text-gray-300">{{ auth()->user()->name }}</span>
        @if (session('counter_id'))
        <a href="{{ route('officer.counter.select') }}" class="text-xs text-gray-400 hover:text-white">Ganti Loket</a>
        @endif
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-xs text-gray-400 hover:text-white">Keluar</button>
        </form>
    </div>
</nav>

<main class="p-4 md:p-6">
    @if (session('success'))
        <div class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50" role="alert">
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50" role="alert">
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    @yield('content')
</main>

@stack('scripts')
</body>
</html>
