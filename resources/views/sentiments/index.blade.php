<!-- resources/views/sentiments/index.blade.php -->
@extends('layouts.app')

@section('title', 'Analisis Sentimen')

@section('content')
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-white mb-2 drop-shadow-lg">Prediksi Kepuasan Pelanggan</h1>
        <p class="text-white/80 max-w-2xl mx-auto">
            Analisis sentimen untuk memahami tingkat kepuasan pelanggan Anda berdasarkan feedback yang diberikan
        </p>
    </div>

    <div class="bg-white/20 backdrop-blur-md rounded-xl p-1 mb-8 inline-flex">
        <a href="{{ route('sentiments.index') }}" class="px-4 py-2 rounded-lg {{ request()->routeIs('sentiments.index') ? 'bg-white text-blue-600 shadow-lg' : 'text-white' }} transition-all duration-300 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 12h12M8 8l-4 4 4 4"/>
            </svg>
            <span>Input Sentimen</span>
        </a>
        <a href="{{ route('sentiments.dashboard') }}" class="px-4 py-2 rounded-lg {{ request()->routeIs('sentiments.dashboard') ? 'bg-white text-blue-600 shadow-lg' : 'text-white' }} transition-all duration-300 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2"/>
                <path d="M3 9h18"/>
                <path d="M9 21V9"/>
            </svg>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('sentiments.history') }}" class="px-4 py-2 rounded-lg {{ request()->routeIs('sentiments.history') ? 'bg-white text-blue-600 shadow-lg' : 'text-white' }} transition-all duration-300 flex items-center gap-2">
            <svg xmlns="http://www.  }} transition-all duration-300 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 3v18h18"/>
                <path d="m19 9-5 5-4-4-3 3"/>
            </svg>
            <span>Riwayat Analisis</span>
        </a>
    </div>

    <div class="grid md:grid-cols-2 gap-8">
        <div class="glass-card card-hover rounded-xl overflow-hidden border-none shadow-xl">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-6">
                <h2 class="text-xl font-bold">Analisis Sentimen</h2>
                <p class="text-white/80">Masukkan feedback pelanggan untuk menganalisis sentimen</p>
            </div>
            <div class="p-6">
                <form action="{{ route('sentiments.analyze') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <textarea name="text" rows="5" class="w-full rounded-md border-2 border-indigo-100 focus:border-indigo-300 p-3 transition-all" placeholder="Masukkan feedback pelanggan di sini...">{{ old('text') }}</textarea>
                        @error('text')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white px-4 py-2 rounded-md transition-all duration-300 shadow-md flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m22 2-7 20-4-9-9-4Z"/>
                                <path d="M22 2 11 13"/>
                            </svg>
                            <span>Analisis Sentimen</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if(session('result'))
            @php
                $result = session('result');
                $glowClass = $result['sentiment'] === 'positive' ? 'glow-positive' : ($result['sentiment'] === 'negative' ? 'glow-negative' : 'glow-neutral');
                $headerClass = $result['sentiment'] === 'positive' ? 'bg-gradient-to-r from-emerald-500 to-green-500 text-white' : ($result['sentiment'] === 'negative' ? 'bg-gradient-to-r from-red-500 to-rose-500 text-white' : 'bg-gradient-to-r from-amber-400 to-yellow-500 text-white');
                $iconBgClass = $result['sentiment'] === 'positive' ? 'bg-green-100 text-green-600' : ($result['sentiment'] === 'negative' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-600');
                $barClass = $result['sentiment'] === 'positive' ? 'bg-gradient-to-r from-green-400 to-green-600' : ($result['sentiment'] === 'negative' ? 'bg-gradient-to-r from-red-400 to-red-600' : 'bg-gradient-to-r from-yellow-400 to-yellow-600');
                $badgeClass = $result['sentiment'] === 'positive' ? 'bg-green-500' : ($result['sentiment'] === 'negative' ? 'bg-red-500' : 'bg-yellow-500');
                $sentimentText = $result['sentiment'] === 'positive' ? 'Positif (Puas)' : ($result['sentiment'] === 'negative' ? 'Negatif (Tidak Puas)' : 'Netral');
                $interpretationText = $result['sentiment'] === 'positive' ? 'Pelanggan menunjukkan kepuasan terhadap produk atau layanan. Umpan balik ini sangat positif.' : ($result['sentiment'] === 'negative' ? 'Pelanggan tidak puas dengan pengalaman mereka. Perlu tindak lanjut untuk perbaikan layanan.' : 'Pelanggan memiliki pendapat netral. Ada ruang untuk peningkatan kepuasan.');
            @endphp

            <div class="glass-card card-hover rounded-xl overflow-hidden border-none shadow-xl {{ $glowClass }}">
                <div class="{{ $headerClass }} p-6">
                    <h2 class="text-xl font-bold">Hasil Analisis</h2>
                </div>
                <div class="p-6">
                    <div class="flex flex-col gap-6">
                        <div class="flex items-center gap-4">
                            <div class="p-4 rounded-full {{ $iconBgClass }}">
                                @if($result['sentiment'] === 'positive')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                        <path d="M8 14s1.5 2 4 2 4-2 4-2"/>
                                        <line x1="9" x2="9.01" y1="9" y2="9"/>
                                        <line x1="15" x2="15.01" y1="9" y2="9"/>
                                    </svg>
                                @elseif($result['sentiment'] === 'negative')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                        <line x1="8" x2="16" y1="15" y2="15"/>
                                        <line x1="9" x2="9.01" y1="9" y2="9"/>
                                        <line x1="15" x2="15.01" y1="9" y2="9"/>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                        <line x1="8" x2="16" y1="12" y2="12"/>
                                        <line x1="9" x2="9.01" y1="9" y2="9"/>
                                        <line x1="15" x2="15.01" y1="9" y2="9"/>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-medium mb-1">Sentimen:</p>
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $badgeClass }} text-white">
                                    {{ $sentimentText }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium mb-2">Tingkat Kepercayaan:</p>
                            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                <div class="h-3 rounded-full {{ $barClass }}" style="width: {{ round($result['probability'] * 100) }}%; transition: width 1s ease-in-out;"></div>
                            </div>
                            <p class="text-xs text-right mt-1 font-bold">{{ round($result['probability'] * 100) }}%</p>
                        </div>
                        <div class="mt-2 p-4 rounded-lg bg-gray-50">
                            <p class="text-sm font-medium mb-2">Interpretasi:</p>
                            <p class="text-sm">{{ $interpretationText }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection