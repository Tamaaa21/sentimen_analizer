<!-- resources/views/sentiments/history.blade.php -->
@extends('layouts.app')

@section('title', 'Riwayat Analisis')

@section('content')
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-white mb-2 drop-shadow-lg">Riwayat Analisis Sentimen</h1>
        <p class="text-white/80 max-w-2xl mx-auto">
            Daftar sentimen yang telah dianalisis sebelumnya
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
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 3v18h18"/>
                <path d="m19 9-5 5-4-4-3 3"/>
            </svg>
            <span>Riwayat Analisis</span>
        </a>
    </div>

    <div class="glass-card card-hover rounded-xl overflow-hidden border-none shadow-xl">
        <div class="bg-gradient-to-r from-blue-500 to-cyan-600 text-white p-6">
            <h2 class="text-xl font-bold">Riwayat Analisis Sentimen</h2>
            <p class="text-white/80">Daftar sentimen yang telah dianalisis sebelumnya</p>
        </div>
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium">Data Sentimen</h3>
                @if(count($sentiments) > 0)
                    <div class="flex gap-2">
                        <a href="{{ route('sentiments.print') }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 6 2 18 2 18 9"/>
                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                                <rect x="6" y="14" width="12" height="8"/>
                            </svg>
                            <span class="hidden sm:inline">Cetak</span>
                        </a>
                        <a href="{{ route('sentiments.export') }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            <span class="hidden sm:inline">Ekspor CSV</span>
                        </a>
                    </div>
                @endif
            </div>

            <div class="overflow-auto rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Teks</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Sentimen</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kepercayaan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Waktu</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if(count($sentiments) === 0)
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mb-2 text-gray-400">
                                            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/>
                                            <polyline points="14 2 14 8 20 8"/>
                                        </svg>
                                        <p>Belum ada data sentimen. Mulai analisis sentimen pada tab Input Sentimen.</p>
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach($sentiments as $index => $sentiment)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 max-w-xs break-words">
                                        {{ \Illuminate\Support\Str::limit($sentiment->text, 50) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $badgeClass = $sentiment->sentiment === 'positive' ? 'bg-gradient-to-r from-green-500 to-green-600' : ($sentiment->sentiment === 'negative' ? 'bg-gradient-to-r from-red-500 to-red-600' : 'bg-gradient-to-r from-yellow-500 to-yellow-600');
                                            $sentimentText = $sentiment->sentiment === 'positive' ? 'Positif' : ($sentiment->sentiment === 'negative' ? 'Negatif' : 'Netral');
                                        @endphp
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $badgeClass }} text-white">
                                            {{ $sentimentText }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                                @php
                                                    $barClass = $sentiment->sentiment === 'positive' ? 'bg-green-500' : ($sentiment->sentiment === 'negative' ? 'bg-red-500' : 'bg-yellow-500');
                                                @endphp
                                                <div class="h-2 rounded-full {{ $barClass }}" style="width: {{ round($sentiment->probability * 100) }}%;"></div>
                                            </div>
                                            <span>{{ round($sentiment->probability * 100) }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $sentiment->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('sentiments.edit', $sentiment->id) }}" class="p-1 rounded-full hover:bg-blue-100 hover:text-blue-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                </svg>
                                            </a>
                                            <form action="{{ route('sentiments.destroy', $sentiment->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 rounded-full hover:bg-red-100 hover:text-red-600" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M3 6h18"/>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
                                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                                        <line x1="10" y1="11" x2="10" y2="17"/>
                                                        <line x1="14" y1="11" x2="14" y2="17"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Import Form -->
            <div class="mt-8 p-4 bg-white rounded-lg shadow">
                <h3 class="text-lg font-medium mb-4">Impor Data</h3>
                <form action="{{ route('sentiments.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="flex items-center gap-4">
                        <div class="flex-1">
                            <input type="file" name="file" accept=".csv,.json" class="w-full p-2 border border-gray-300 rounded-md">
                            @error('file')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white px-4 py-2 rounded-md transition-all duration-300 shadow-md flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                            <span>Impor Data</span>
                        </button>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Format yang didukung: CSV dan JSON. Kolom yang diperlukan: text, sentiment.</p>
                </form>
            </div>

            @if(session('import_stats'))
                <div class="mt-4 p-4 bg-green-50 border border-green-100 rounded-lg">
                    <div class="flex items-center gap-2 text-green-700 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        <h4 class="font-medium">Impor Data Berhasil</h4>
                    </div>
                    <div class="text-sm text-green-600">
                        <p>Total data: <strong>{{ session('import_stats')['total'] }}</strong></p>
                        <p>Berhasil diimpor: <strong>{{ session('import_stats')['success'] }}</strong></p>
                        @if(session('import_stats')['failed'] > 0)
                            <p>Gagal diimpor: <strong>{{ session('import_stats')['failed'] }}</strong></p>
                        @endif
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="mt-4 p-4 bg-green-50 border border-green-100 rounded-lg">
                    <div class="flex items-center gap-2 text-green-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mt-4 p-4 bg-red-50 border border-red-100 rounded-lg">
                    <div class="flex items-center gap-2 text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection