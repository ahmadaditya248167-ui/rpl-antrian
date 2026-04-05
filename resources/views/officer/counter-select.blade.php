@extends('layouts.officer')
@section('title', 'Pilih Loket')

@section('content')
<div class="max-w-lg mx-auto mt-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Pilih Loket Anda</h2>
        <p class="text-sm text-gray-500 mb-6">Pilih loket yang akan Anda tangani hari ini ({{ today()->isoformat('D MMMM Y') }}).</p>

        <form method="POST" action="{{ route('officer.counter.store') }}" class="space-y-4">
            @csrf
            <div class="grid gap-3">
                @foreach ($counters as $counter)
                <label class="flex items-start gap-3 border border-gray-200 rounded-lg p-4 cursor-pointer hover:border-blue-500 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                    <input type="radio" name="counter_id" value="{{ $counter->id }}" class="mt-1 text-blue-600">
                    <div>
                        <p class="font-semibold text-gray-800 dark:text-white">{{ $counter->name }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Layanan: {{ $counter->services->pluck('name')->join(', ') ?: '—' }}
                        </p>
                    </div>
                </label>
                @endforeach
            </div>
            @error('counter_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

            <button type="submit"
                class="w-full text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 mt-4">
                Mulai Bertugas
            </button>
        </form>
    </div>
</div>
@endsection
