@extends('layouts.app')

@section('title', 'Analisis Sentimen')

@section('content')
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Prediksi Kepuasan Pelanggan</h1>
                <p class="text-gray-600 mt-1">
                    Analisis sentimen untuk memahami tingkat kepuasan pelanggan Anda
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">Last analyzed:</span>
                <span class="text-sm font-medium text-indigo-600">
                    @if(session('result'))
                        {{ now()->format('d M Y, H:i') }}
                    @else
                        No data yet
                    @endif
                </span>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-8">
        <!-- Input Card -->
        <div class="glass-card card-hover rounded-xl overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-6">
                <div class="flex items-center gap-3">
                    <i class="fas fa-comment-alt text-white text-xl"></i>
                    <h2 class="text-xl font-bold">Analisis Sentimen</h2>
                </div>
                <p class="text-white/80 mt-1">Masukkan feedback pelanggan untuk menganalisis sentimen</p>
            </div>
            <div class="p-6">
                <form action="{{ route('sentiments.analyze') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="feedback-text" class="block text-sm font-medium text-gray-700 mb-2">Feedback Pelanggan</label>
                        <textarea name="text" id="feedback-text" rows="5" class="w-full rounded-lg border border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 p-3 transition-all" placeholder="Masukkan feedback pelanggan di sini...">{{ old('text') }}</textarea>
                        @error('text')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white px-6 py-2.5 rounded-lg transition-all duration-300 shadow-md flex items-center gap-2">
                            <i class="fas fa-analytics"></i>
                            <span>Analisis Sentimen</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Result Card -->
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

            <div class="glass-card card-hover rounded-xl overflow-hidden border border-gray-200 {{ $glowClass }}">
                <div class="{{ $headerClass }} p-6">
                    <div class="flex items-center gap-3">
                        @if($result['sentiment'] === 'positive')
                            <i class="fas fa-smile text-white text-xl"></i>
                        @elseif($result['sentiment'] === 'negative')
                            <i class="fas fa-frown text-white text-xl"></i>
                        @else
                            <i class="fas fa-meh text-white text-xl"></i>
                        @endif
                        <h2 class="text-xl font-bold">Hasil Analisis</h2>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex flex-col gap-6">
                        <!-- Sentiment Badge -->
                        <div class="flex items-center gap-4">
                            <div class="p-4 rounded-full {{ $iconBgClass }}">
                                @if($result['sentiment'] === 'positive')
                                    <i class="fas fa-smile text-xl"></i>
                                @elseif($result['sentiment'] === 'negative')
                                    <i class="fas fa-frown text-xl"></i>
                                @else
                                    <i class="fas fa-meh text-xl"></i>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Sentimen:</p>
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold {{ $badgeClass }} text-white">
                                    {{ $sentimentText }}
                                </span>
                            </div>
                        </div>

                        <!-- Confidence Meter -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <p class="text-sm font-medium text-gray-500">Tingkat Kepercayaan:</p>
                                <span class="text-sm font-bold text-indigo-600">{{ round($result['probability'] * 100) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
<div class="h-2.5 rounded-full {{ $barClass }}" data-width="{{ round($result['probability'] * 100) }}"></div>                            </div>
                        </div>

                        <!-- Interpretation -->
                        <div class="mt-2 p-4 rounded-lg bg-gray-50 border border-gray-200">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-lightbulb text-indigo-500"></i>
                                <p class="text-sm font-medium text-gray-700">Interpretasi:</p>
                            </div>
                            <p class="text-sm text-gray-600">{{ $interpretationText }}</p>
                        </div>

                        <!-- Feedback Preview -->
                        <div class="mt-2">
                            <p class="text-sm font-medium text-gray-500 mb-2">Feedback yang dianalisis:</p>
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200 max-h-32 overflow-y-auto">
                                <p class="text-sm text-gray-600">{{ session('text') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="glass-card card-hover rounded-xl overflow-hidden border border-gray-200 flex items-center justify-center min-h-full">
                <div class="text-center p-8">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 mb-4">
                        <i class="fas fa-comment-dots text-indigo-600"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">Belum ada hasil analisis</h3>
                    <p class="text-sm text-gray-500">Masukkan feedback pelanggan di form sebelah untuk melihat hasil analisis sentimen.</p>
                </div>
            </div>
        @endif
    </div>
@endsection