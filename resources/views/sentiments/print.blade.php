<!-- resources/views/sentiments/print.blade.php -->
@extends('layouts.app')

@section('title', 'Cetak Laporan')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-lg max-w-5xl mx-auto print:shadow-none print:p-0">
    <div class="print:hidden mb-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Laporan Analisis Sentimen</h1>
        <div class="flex gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"/>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                    <rect x="6" y="14" width="12" height="8"/>
                </svg>
                <span>Cetak</span>
            </button>
            <a href="{{ route('sentiments.history') }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-200 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 19-7-7 7-7"/>
                    <path d="M19 12H5"/>
                </svg>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Header for print only -->
    <div class="hidden print:block mb-6">
        <h1 class="text-2xl font-bold">Laporan Analisis Sentimen</h1>
        <p class="text-gray-500">Tanggal: {{ date('d F Y') }}</p>
    </div>

    <!-- Summary -->
    <div class="bg-gray-50 p-4 rounded-lg border print:bg-white mb-8">
        <h2 class="text-lg font-semibold mb-4">Ringkasan</h2>
        <div class="grid grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-lg shadow-sm border print:border-gray-300">
                <p class="text-sm text-gray-500">Total Data</p>
                <p class="text-2xl font-bold">{{ count($sentiments) }}</p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg shadow-sm border border-green-100 print:bg-white print:border-gray-300">
                <p class="text-sm text-green-600 print:text-gray-500">Sentimen Positif</p>
                <p class="text-2xl font-bold text-green-600 print:text-black">{{ $sentimentCounts['positive'] }}</p>
                <p class="text-xs text-green-500 print:text-gray-500">
                    {{ count($sentiments) > 0 ? round(($sentimentCounts['positive'] / count($sentiments)) * 100) : 0 }}% dari total
                </p>
            </div>
            <div class="bg-yellow-50 p-4 rounded-lg shadow-sm border border-yellow-100 print:bg-white print:border-gray-300">
                <p class="text-sm text-yellow-600 print:text-gray-500">Sentimen Netral</p>
                <p class="text-2xl font-bold text-yellow-600 print:text-black">{{ $sentimentCounts['neutral'] }}</p>
                <p class="text-xs text-yellow-500 print:text-gray-500">
                    {{ count($sentiments) > 0 ? round(($sentimentCounts['neutral'] / count($sentiments)) * 100) : 0 }}% dari total
                </p>
            </div>
            <div class="bg-red-50 p-4 rounded-lg shadow-sm border border-red-100 print:bg-white print:border-gray-300">
                <p class="text-sm text-red-600 print:text-gray-500">Sentimen Negatif</p>
                <p class="text-2xl font-bold text-red-600 print:text-black">{{ $sentimentCounts['negative'] }}</p>
                <p class="text-xs text-red-500 print:text-gray-500">
                    {{ count($sentiments) > 0 ? round(($sentimentCounts['negative'] / count($sentiments)) * 100) : 0 }}% dari total
                </p>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div>
        <h2 class="text-lg font-semibold mb-4">Data Analisis Sentimen</h2>
        <div class="overflow-auto">
            <table class="min-w-full divide-y divide-gray-200 border print:border-gray-300">
                <thead class="bg-gray-50 print:bg-white">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teks</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sentimen</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kepercayaan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($sentiments as $index => $sentiment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs break-words">{{ $sentiment->text }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $badgeClass = $sentiment->sentiment === 'positive' ? 'bg-green-100 text-green-800 border-green-200' : ($sentiment->sentiment === 'negative' ? 'bg-red-100 text-red-800 border-red-200' : 'bg-yellow-100 text-yellow-800 border-yellow-200');
                                    $sentimentText = $sentiment->sentiment === 'positive' ? 'Positif' : ($sentiment->sentiment === 'negative' ? 'Negatif' : 'Netral');
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $badgeClass }} print:bg-white print:text-black print:border">
                                    {{ $sentimentText }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ round($sentiment->probability * 100) }}%
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $sentiment->created_at->format('d M Y, H:i') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer for print only -->
    <div class="hidden print:block mt-8 pt-8 border-t text-center text-gray-500 text-sm">
        <p>Laporan ini dibuat menggunakan Aplikasi Analisis Sentimen</p>
        <p>© {{ date('Y') }} Sentiment Analyzer</p>
    </div>
</div>
@endsection