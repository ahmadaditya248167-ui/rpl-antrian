<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="10">
    <title>Display Antrian</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background: #0f172a; }
        .marquee-text { animation: pulse 2s ease-in-out infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.7} }
    </style>
</head>
<body class="min-h-screen text-white">

    {{-- Header --}}
    <header class="bg-slate-900 border-b border-slate-700 px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-white">Papan Antrian</h1>
                <p class="text-sm text-slate-400">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div id="clock" class="text-3xl font-mono font-bold text-blue-400"></div>
            <a href="{{ route('public.index') }}"
               class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                Ambil Nomor
            </a>
        </div>
    </header>

    {{-- Content --}}
    <main class="p-6">

        @if($services->isEmpty())
            <div class="flex items-center justify-center h-64">
                <p class="text-slate-400 text-2xl">Tidak ada layanan aktif hari ini.</p>
            </div>
        @else
            <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 xl:grid-cols-3">
                @foreach($services as $service)
                    @php
                        $colors = [
                            'bg' => [
                                'A' => 'from-blue-900 to-blue-800 border-blue-700',
                                'B' => 'from-green-900 to-green-800 border-green-700',
                                'C' => 'from-purple-900 to-purple-800 border-purple-700',
                                'D' => 'from-orange-900 to-orange-800 border-orange-700',
                                'E' => 'from-rose-900 to-rose-800 border-rose-700',
                            ],
                            'num' => [
                                'A' => 'text-blue-300',
                                'B' => 'text-green-300',
                                'C' => 'text-purple-300',
                                'D' => 'text-orange-300',
                                'E' => 'text-rose-300',
                            ],
                            'badge' => [
                                'A' => 'bg-blue-700 text-blue-100',
                                'B' => 'bg-green-700 text-green-100',
                                'C' => 'bg-purple-700 text-purple-100',
                                'D' => 'bg-orange-700 text-orange-100',
                                'E' => 'bg-rose-700 text-rose-100',
                            ],
                        ];
                        $letter   = strtoupper(substr($service->prefix, 0, 1));
                        $bgClass  = $colors['bg'][$letter]  ?? 'from-slate-800 to-slate-700 border-slate-600';
                        $numClass = $colors['num'][$letter] ?? 'text-slate-300';
                        $badgeClass = $colors['badge'][$letter] ?? 'bg-slate-600 text-slate-100';
                    @endphp

                    <div class="bg-gradient-to-br {{ $bgClass }} border rounded-2xl overflow-hidden shadow-2xl">

                        {{-- Service header --}}
                        <div class="px-6 pt-5 pb-3 flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-bold text-white">{{ $service->name }}</h2>
                                <span class="inline-block mt-1 text-xs font-semibold px-2 py-0.5 rounded {{ $badgeClass }}">
                                    Prefix: {{ $service->prefix }}
                                </span>
                            </div>
                            @if($service->is_open_today)
                                <span class="text-xs font-semibold px-3 py-1 rounded-full bg-green-600 text-green-100">Buka</span>
                            @else
                                <span class="text-xs font-semibold px-3 py-1 rounded-full bg-red-700 text-red-100">Tutup</span>
                            @endif
                        </div>

                        {{-- Separator --}}
                        <div class="mx-6 border-t border-white/10"></div>

                        {{-- Serving numbers --}}
                        <div class="px-6 py-4">
                            <p class="text-xs uppercase tracking-widest text-white/50 mb-2">Sedang Dilayani</p>
                            @if($service->now_serving->isEmpty())
                                <div class="flex items-center justify-center h-20">
                                    <span class="text-4xl font-mono font-black text-white/20 tracking-widest">— — —</span>
                                </div>
                            @else
                                <div class="space-y-2">
                                    @foreach($service->now_serving as $q)
                                        <div class="flex items-center justify-between bg-black/30 rounded-xl px-4 py-3">
                                            <span class="text-5xl font-black font-mono {{ $numClass }} marquee-text tracking-wider">
                                                {{ $q->queue_code }}
                                            </span>
                                            <div class="text-right">
                                                <p class="text-white/50 text-xs uppercase tracking-wide">Loket</p>
                                                <p class="text-white font-bold text-lg">{{ $q->counter->name ?? '—' }}</p>
                                                @if($q->visitor_name)
                                                    <p class="text-white/60 text-sm mt-0.5">{{ $q->visitor_name }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Stats footer --}}
                        <div class="px-6 pb-5 grid grid-cols-3 gap-3">
                            <div class="bg-black/25 rounded-xl p-3 text-center">
                                <p class="text-xs text-white/40 uppercase tracking-wide">Menunggu</p>
                                <p class="text-2xl font-black text-white mt-1">{{ $service->waiting_count }}</p>
                            </div>
                            <div class="bg-black/25 rounded-xl p-3 text-center">
                                <p class="text-xs text-white/40 uppercase tracking-wide">Selesai</p>
                                <p class="text-2xl font-black text-green-300 mt-1">{{ $service->done_count }}</p>
                            </div>
                            <div class="bg-black/25 rounded-xl p-3 text-center">
                                <p class="text-xs text-white/40 uppercase tracking-wide">Sisa Kuota</p>
                                <p class="text-2xl font-black {{ $service->remaining_quota > 0 ? 'text-yellow-300' : 'text-red-400' }} mt-1">
                                    {{ $service->is_open_today ? $service->remaining_quota : '—' }}
                                </p>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </main>

    {{-- Live clock --}}
    <script>
        function updateClock() {
            const now = new Date();
            const h = String(now.getHours()).padStart(2,'0');
            const m = String(now.getMinutes()).padStart(2,'0');
            const s = String(now.getSeconds()).padStart(2,'0');
            document.getElementById('clock').textContent = h + ':' + m + ':' + s;
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>

    {{-- Countdown refresh indicator --}}
    <div class="fixed bottom-4 right-4 flex items-center gap-2 bg-slate-800 border border-slate-700 rounded-full px-4 py-2 text-sm text-slate-400">
        <span class="w-2 h-2 rounded-full bg-green-400 animate-ping inline-block"></span>
        Refresh otomatis setiap 10 detik
    </div>

</body>
</html>
