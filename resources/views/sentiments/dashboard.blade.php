<!-- resources/views/sentiments/dashboard.blade.php -->
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-black mb-2 drop-shadow-lg">Dashboard Analisis Sentimen</h1>
        <p class="text-black/80 max-w-2xl mx-auto">
            Visualisasi data sentimen pelanggan
        </p>
    </div>

   

    <div class="grid md:grid-cols-2 gap-8">
        <div class="glass-card card-hover rounded-xl overflow-hidden border-none shadow-xl">
            <div class="bg-gradient-to-r from-violet-500 to-purple-600 text-white p-6">
                <h2 class="text-xl font-bold">Distribusi Sentimen</h2>
                <p class="text-white/80">Perbandingan sentimen pelanggan</p>
            </div>
            <div class="p-6 h-80">
                <canvas id="barChart"></canvas>
            </div>
        </div>

        <div class="glass-card card-hover rounded-xl overflow-hidden border-none shadow-xl">
            <div class="bg-gradient-to-r from-fuchsia-500 to-pink-600 text-white p-6">
                <h2 class="text-xl font-bold">Proporsi Sentimen</h2>
                <p class="text-white/80">Komposisi sentimen keseluruhan</p>
            </div>
            <div class="p-6 h-80">
                <canvas id="pieChart"></canvas>
            </div>
        </div>

        <div class="md:col-span-2 glass-card card-hover rounded-xl overflow-hidden border-none shadow-xl">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-6">
                <h2 class="text-xl font-bold">Ringkasan Sentimen</h2>
                <p class="text-white/80">Analisis kepuasan pelanggan</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl shadow-sm border border-green-200">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="p-2 bg-green-500 rounded-lg text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="M8 14s1.5 2 4 2 4-2 4-2"/>
                                    <line x1="9" x2="9.01" y1="9" y2="9"/>
                                    <line x1="15" x2="15.01" y1="9" y2="9"/>
                                </svg>
                            </div>
                            <h3 class="font-medium text-green-700">Positif</h3>
                        </div>
                        <p class="text-3xl font-bold text-green-600">{{ $sentimentCounts['positive'] }}</p>
                        <p class="text-sm text-green-600/70 mt-1">
                            {{ count($sentiments) > 0 ? round(($sentimentCounts['positive'] / count($sentiments)) * 100) : 0 }}% dari total
                        </p>
                    </div>

                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-6 rounded-xl shadow-sm border border-yellow-200">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="p-2 bg-yellow-500 rounded-lg text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="8" x2="16" y1="12" y2="12"/>
                                    <line x1="9" x2="9.01" y1="9" y2="9"/>
                                    <line x1="15" x2="15.01" y1="9" y2="9"/>
                                </svg>
                            </div>
                            <h3 class="font-medium text-yellow-700">Netral</h3>
                        </div>
                        <p class="text-3xl font-bold text-yellow-600">{{ $sentimentCounts['neutral'] }}</p>
                        <p class="text-sm text-yellow-600/70 mt-1">
                            {{ count($sentiments) > 0 ? round(($sentimentCounts['neutral'] / count($sentiments)) * 100) : 0 }}% dari total
                        </p>
                    </div>

                    <div class="bg-gradient-to-br from-red-50 to-red-100 p-6 rounded-xl shadow-sm border border-red-200">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="p-2 bg-red-500 rounded-lg text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="8" x2="16" y1="15" y2="15"/>
                                    <line x1="9" x2="9.01" y1="9" y2="9"/>
                                    <line x1="15" x2="15.01" y1="9" y2="9"/>
                                </svg>
                            </div>
                            <h3 class="font-medium text-red-700">Negatif</h3>
                        </div>
                        <p class="text-3xl font-bold text-red-600">{{ $sentimentCounts['negative'] }}</p>
                        <p class="text-sm text-red-600/70 mt-1">
                            {{ count($sentiments) > 0 ? round(($sentimentCounts['negative'] / count($sentiments)) * 100) : 0 }}% dari total
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Bar Chart
        const barCtx = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['Positif', 'Netral', 'Negatif'],
                datasets: [{
                    label: 'Jumlah Ulasan',
                    data: [{{ $sentimentCounts['positive'] }}, {{ $sentimentCounts['neutral'] }}, {{ $sentimentCounts['negative'] }}],
                    backgroundColor: [
                        '#22c55e', // green-500
                        '#eab308', // yellow-500
                        '#ef4444', // red-500
                    ],
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

        // Pie Chart
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['Positif', 'Netral', 'Negatif'],
                datasets: [{
                    data: [{{ $sentimentCounts['positive'] }}, {{ $sentimentCounts['neutral'] }}, {{ $sentimentCounts['negative'] }}],
                    backgroundColor: [
                        '#22c55e', // green-500
                        '#eab308', // yellow-500
                        '#ef4444', // red-500
                    ],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });
    });
</script>
@endpush