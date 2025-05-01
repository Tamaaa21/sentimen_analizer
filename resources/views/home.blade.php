<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentiment Analyzer - Analisis Sentimen Teks</title>
    <meta name="description" content="Analisis sentimen untuk memahami tingkat kepuasan pelanggan berdasarkan feedback">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .animated-gradient {
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="sticky top-0 z-50 w-full border-b bg-white/95 backdrop-blur">
        <div class="container mx-auto flex h-16 items-center justify-between px-4">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 text-blue-600">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M9.082 16.007c.36.18.711.327 1.082.429m3.754-1.422c.36.18.711.327 1.082.429M9.082 7.007c.36-.18.711-.327 1.082-.429m4.836 1.85a5.5 5.5 0 0 1-1.082-.429"/>
                    <path d="M7 16.5c0-1 1.5-2 3-2s2.5 1 2.5 2m2-6.5c0-1 1.5-2 3-2s2.5 1 2.5 2"/>
                </svg>
                <a href="{{ route('home') }}" class="flex items-center gap-1">
                    <span class="font-bold text-lg hidden sm:inline-block">Sentiment Analyzer</span>
                </a>
            </div>
            <nav class="flex items-center gap-4">
                @if(Auth::check())
                    <a href="{{ route('sentiments.index') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Analisis</a>
                    <a href="{{ route('sentiments.dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Dashboard</a>
                    <a href="{{ route('sentiments.history') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Riwayat</a>
                    
                    <!-- User Dropdown -->
                    <div class="relative ml-3">
                        <div>
                            <button type="button" class="flex items-center gap-x-1 text-sm font-medium text-gray-700 hover:text-gray-800" id="user-menu-button" aria-expanded="false" aria-haspopup="true" onclick="document.getElementById('user-dropdown').classList.toggle('hidden')">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <div id="user-dropdown" class="hidden absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Login</a>
                    <a href="{{ route('register') }}" class="rounded-md bg-blue-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                        Daftar
                    </a>
                @endif
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="animated-gradient py-20 md:pt-32 md:pb-16 px-4">
        <div class="container mx-auto max-w-6xl">
            <div class="grid md:grid-cols-2 gap-10 items-center">
                <div class="text-white space-y-6">
                    <h1 class="text-4xl md:text-5xl font-bold tracking-tight">
                        Analisis Sentimen Teks untuk Keputusan yang Lebih Baik
                    </h1>
                    <p class="text-lg md:text-xl text-white/90 max-w-xl">
                        Temukan nilai dalam feedback pelanggan Anda dengan analisis sentimen yang akurat dan cepat. Mengidentifikasi emosi positif, netral, dan negatif dalam teks bahasa Indonesia.
                    </p>
                    <div class="flex flex-wrap gap-4 pt-4">
                        @if(Auth::check())
                            <a href="{{ route('sentiments.index') }}" class="rounded-md bg-white px-4 py-3 text-sm font-semibold text-blue-600 shadow-sm hover:bg-gray-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                                Mulai Analisis
                            </a>
                            <a href="{{ route('sentiments.dashboard') }}" class="rounded-md border border-white bg-transparent px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-white/10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                                Lihat Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="rounded-md bg-white px-4 py-3 text-sm font-semibold text-blue-600 shadow-sm hover:bg-gray-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                                Daftar Sekarang
                            </a>
                            <a href="{{ route('login') }}" class="rounded-md border border-white bg-transparent px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-white/10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                                Login
                            </a>
                        @endif
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="glass-card rounded-xl overflow-hidden shadow-xl">
                        <div class="p-6 space-y-4">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                <div class="ml-2 text-gray-500 text-sm">Analisis Sentimen Demo</div>
                            </div>
                            <div class="bg-gray-100 p-4 rounded-lg">
                                <div class="font-mono text-sm text-gray-700">
                                    <div class="mb-2">// Contoh analisis sentimen</div>
                                    <div><span class="text-blue-600">const</span> text = <span class="text-green-600">"Pelayanan sangat memuaskan, sangat recommended!"</span>;</div>
                                    <div><span class="text-blue-600">const</span> hasil = <span class="text-purple-600">analyzeSentiment</span>(text);</div>
                                    <div class="mt-2">hasil → {</div>
                                    <div class="ml-4">sentiment: <span class="text-green-600">"positive"</span>,</div>
                                    <div class="ml-4">probability: <span class="text-orange-600">0.92</span></div>
                                    <div>}</div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <div class="px-3 py-1 bg-green-100 text-green-600 font-medium rounded-md text-sm">
                                    Positif: 92%
                                </div>
                                <div class="px-3 py-1 bg-yellow-100 text-yellow-600 font-medium rounded-md text-sm">
                                    Netral: 6%
                                </div>
                                <div class="px-3 py-1 bg-red-100 text-red-600 font-medium rounded-md text-sm">
                                    Negatif: 2%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Feature Section -->
    <section class="py-16 px-4 bg-white">
        <div class="container mx-auto max-w-6xl">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 mb-4">Fitur Utama</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Menggali wawasan dari data teks belum pernah semudah ini. Hadirkan kekuatan analisis sentimen ke bisnis Anda.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="p-6 border border-gray-200 rounded-lg shadow-sm card-hover">
                    <div class="feature-icon bg-blue-100 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <path d="M9 13h6"/>
                            <path d="M9 17h3"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 text-gray-900">Analisis Teks</h3>
                    <p class="text-gray-600">
                        Analisis teks komentar, ulasan, atau feedback dalam bahasa Indonesia untuk mengidentifikasi sentimen positif, netral, atau negatif.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="p-6 border border-gray-200 rounded-lg shadow-sm card-hover">
                    <div class="feature-icon bg-purple-100 text-purple-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <path d="M3 9h18"/>
                            <path d="M9 21V9"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 text-gray-900">Dashboard Interaktif</h3>
                    <p class="text-gray-600">
                        Visualisasi data sentimen dengan grafik dan diagram interaktif untuk memahami tren dan pola dalam feedback.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="p-6 border border-gray-200 rounded-lg shadow-sm card-hover">
                    <div class="feature-icon bg-green-100 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="17 8 12 3 7 8"/>
                            <line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 text-gray-900">Import & Export</h3>
                    <p class="text-gray-600">
                        Import data dari file CSV atau JSON dan export hasil analisis untuk digunakan dalam aplikasi lain.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="p-6 border border-gray-200 rounded-lg shadow-sm card-hover">
                    <div class="feature-icon bg-red-100 text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <path d="M20.4 14.5 16 10 4 20"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 text-gray-900">Visualisasi Data</h3>
                    <p class="text-gray-600">
                        Lihat distribusi sentimen dengan grafik dan diagram batang & pie untuk memahami proporsi feedback.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="p-6 border border-gray-200 rounded-lg shadow-sm card-hover">
                    <div class="feature-icon bg-yellow-100 text-yellow-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 text-gray-900">Edit & Pengelolaan</h3>
                    <p class="text-gray-600">
                        Edit dan kelola riwayat analisis sentimen dengan mudah, serta hapus data yang tidak diperlukan.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="p-6 border border-gray-200 rounded-lg shadow-sm card-hover">
                    <div class="feature-icon bg-blue-100 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/>
                            <polyline points="14 2 14 8 20 8"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 text-gray-900">Cetak PDF</h3>
                    <p class="text-gray-600">
                        Cetak laporan dalam format PDF untuk berbagi wawasan dengan tim atau stakeholder lainnya.
                    </p>
                </div>
            </div>
        </div>
    </section>

    @if(Auth::check())
    <!-- User Dashboard Summary Section -->
    <section class="py-16 px-4 bg-gray-50">
        <div class="container mx-auto max-w-6xl">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 mb-4">Dashboard Anda</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Ringkasan aktivitas analisis sentimen Anda sejauh ini
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total Sentiments -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500">Total Analisis</h3>
                            <p class="text-2xl font-bold text-gray-900">{{ $sentimentsCount }}</p>
                        </div>
                    </div>
                </div>

                <!-- Positive Sentiments -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M8 14s1.5 2 4 2 4-2 4-2"/>
                                <line x1="9" x2="9.01" y1="9" y2="9"/>
                                <line x1="15" x2="15.01" y1="9" y2="9"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500">Positif</h3>
                            <p class="text-2xl font-bold text-green-600">{{ $positiveSentiments }}</p>
                        </div>
                    </div>
                </div>

                <!-- Neutral Sentiments -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-yellow-100 p-3 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-yellow-600">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="8" x2="16" y1="15" y2="15"/>
                                <line x1="9" x2="9.01" y1="9" y2="9"/>
                                <line x1="15" x2="15.01" y1="9" y2="9"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500">Netral</h3>
                            <p class="text-2xl font-bold text-yellow-600">{{ $neutralSentiments }}</p>
                        </div>
                    </div>
                </div>

                <!-- Negative Sentiments -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-red-100 p-3 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="8" x2="16" y1="15" y2="15"/>
                                <path d="M8.5 9a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                                <path d="M15.5 9a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500">Negatif</h3>
                            <p class="text-2xl font-bold text-red-600">{{ $negativeSentiments }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($recentSentiments) && count($recentSentiments) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Analisis Terbaru</h3>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($recentSentiments as $sentiment)
                            <div class="p-6">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-sm text-gray-600">{{ Str::limit($sentiment->text, 100) }}</p>
                                        <div class="mt-2 flex items-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sentiment->sentiment === 'positive' ? 'bg-green-100 text-green-800' : ($sentiment->sentiment === 'negative' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst($sentiment->sentiment) }}
                                            </span>
                                            <span class="ml-2 text-xs text-gray-500">{{ number_format($sentiment->probability * 100, 0) }}% kepercayaan</span>
                                            <span class="ml-2 text-xs text-gray-500">{{ $sentiment->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="p-4 border-t border-gray-200 bg-gray-50">
                        <a href="{{ route('sentiments.history') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                            Lihat semua riwayat analisis →
                        </a>
                    </div>
                </div>
            @endif

            <div class="flex justify-center mt-8">
                <a href="{{ route('sentiments.index') }}" class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Mulai Analisis Baru
                </a>
            </div>
        </div>
    </section>
    @else
    <!-- Call to Action Section -->
    <section class="py-16 px-4 bg-gray-50">
        <div class="container mx-auto max-w-6xl">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 mb-4">Mulai Analisis Sentimen Sekarang</h2>
                <p class="text-lg text-gray-600 mb-8">
                    Daftar sekarang untuk mengakses fitur lengkap analisis sentimen dan mulai memahami feedback pelanggan Anda dengan lebih baik.
                </p>
                <div class="flex flex-wrap gap-4 justify-center">
                    <a href="{{ route('register') }}" class="rounded-md bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                        Daftar Gratis
                    </a>
                    <a href="{{ route('login') }}" class="rounded-md bg-white px-4 py-3 text-sm font-semibold text-blue-600 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                        Login
                    </a>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- How It Works Section -->
    <section class="py-16 px-4 bg-white">
        <div class="container mx-auto max-w-6xl">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 mb-4">Cara Kerja</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Analisis sentimen yang mudah digunakan untuk mendapatkan wawasan dari feedback pelanggan Anda
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="text-center">
                    <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 text-blue-600 text-2xl font-bold mb-4">
                        1
                    </div>
                    <h3 class="text-lg font-semibold mb-2 text-gray-900">Input Teks</h3>
                    <p class="text-gray-600">
                        Masukkan teks feedback atau komentar pelanggan yang ingin dianalisis, atau import data dari file CSV atau JSON.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="text-center">
                    <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 text-blue-600 text-2xl font-bold mb-4">
                        2
                    </div>
                    <h3 class="text-lg font-semibold mb-2 text-gray-900">Analisis Otomatis</h3>
                    <p class="text-gray-600">
                        Sistem akan menganalisis teks dan mengidentifikasi sentimen (positif, netral, atau negatif) dengan tingkat kepercayaan.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="text-center">
                    <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 text-blue-600 text-2xl font-bold mb-4">
                        3
                    </div>
                    <h3 class="text-lg font-semibold mb-2 text-gray-900">Visualisasi Hasil</h3>
                    <p class="text-gray-600">
                        Lihat hasil analisis dalam bentuk grafik dan dapatkan wawasan yang dapat ditindaklanjuti untuk meningkatkan layanan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section class="py-16 px-4 bg-gray-50">
        <div class="container mx-auto max-w-6xl">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 mb-4">Testimoni Pengguna</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Lihat apa yang dikatakan pengguna tentang aplikasi analisis sentimen kami
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400 flex">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <blockquote class="text-gray-600 mb-4">
                        "Aplikasi ini sangat membantu kami menganalisis feedback pelanggan dengan cepat dan akurat. Visualisasi data memudahkan kami mengambil keputusan berdasarkan sentimen pelanggan."
                    </blockquote>
                    <div class="mt-4">
                        <p class="font-medium text-gray-900">Budi Santoso</p>
                        <p class="text-sm text-gray-500">Customer Experience Manager</p>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400 flex">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <blockquote class="text-gray-600 mb-4">
                        "Fitur import dan export sangat berguna untuk mengintegrasikan dengan sistem yang sudah ada. Dashboard interaktif juga memberikan insight yang bagus tentang tren sentimen pelanggan."
                    </blockquote>
                    <div class="mt-4">
                        <p class="font-medium text-gray-900">Siti Rahayu</p>
                        <p class="text-sm text-gray-500">Marketing Analyst</p>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400 flex">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <blockquote class="text-gray-600 mb-4">
                        "Sebagai UKM, aplikasi ini sangat membantu kami memahami feedback pelanggan. Interface yang user-friendly dan hasil yang akurat membuat kami dapat meningkatkan layanan dengan cepat."
                    </blockquote>
                    <div class="mt-4">
                        <p class="font-medium text-gray-900">Ahmad Hidayat</p>
                        <p class="text-sm text-gray-500">Pemilik Toko Online</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16 px-4 bg-white">
        <div class="container mx-auto max-w-6xl">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 mb-4">Pertanyaan Umum</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Jawaban untuk pertanyaan yang sering diajukan tentang aplikasi analisis sentimen
                </p>
            </div>

            <div class="max-w-3xl mx-auto divide-y divide-gray-200">
                <div class="py-6">
                    <h3 class="text-lg font-medium text-gray-900">Apakah saya perlu memiliki pengetahuan teknis untuk menggunakan aplikasi ini?</h3>
                    <p class="mt-2 text-gray-600">
                        Tidak, aplikasi ini dirancang agar mudah digunakan oleh siapa saja. Interface yang sederhana dan intuitif memungkinkan Anda menganalisis teks tanpa pengetahuan teknis khusus.
                    </p>
                </div>

                <div class="py-6">
                    <h3 class="text-lg font-medium text-gray-900">Apakah aplikasi ini mendukung bahasa Indonesia?</h3>
                    <p class="mt-2 text-gray-600">
                        Ya, aplikasi ini dioptimalkan untuk menganalisis teks dalam bahasa Indonesia dan dapat mendeteksi sentimen dari ulasan, komentar, atau feedback dalam bahasa Indonesia.
                    </p>
                </div>

                <div class="py-6">
                    <h3 class="text-lg font-medium text-gray-900">Apakah data saya aman?</h3>
                    <p class="mt-2 text-gray-600">
                        Ya, kami mengutamakan keamanan data. Setiap pengguna hanya dapat mengakses data mereka sendiri, dan kami menggunakan enkripsi untuk melindungi informasi Anda.
                    </p>
                </div>

                <div class="py-6">
                    <h3 class="text-lg font-medium text-gray-900">Bagaimana cara mengexport data hasil analisis?</h3>
                    <p class="mt-2 text-gray-600">
                        Anda dapat mengexport data hasil analisis dalam format CSV melalui halaman riwayat. Fitur ini memudahkan Anda untuk menggunakan data di aplikasi lain seperti Excel atau PowerPoint.
                    </p>
                </div>

                <div class="py-6">
                    <h3 class="text-lg font-medium text-gray-900">Berapa banyak teks yang dapat saya analisis?</h3>
                    <p class="mt-2 text-gray-600">
                        Tidak ada batasan jumlah teks yang dapat dianalisis. Anda dapat menganalisis teks satu per satu atau melakukan import data dalam jumlah besar melalui file CSV atau JSON.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t bg-white">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 text-blue-600">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M9.082 16.007c.36.18.711.327 1.082.429m3.754-1.422c.36.18.711.327 1.082.429M9.082 7.007c.36-.18.711-.327 1.082-.429m4.836 1.85a5.5 5.5 0 0 1-1.082-.429"/>
                            <path d="M7 16.5c0-1 1.5-2 3-2s2.5 1 2.5 2m2-6.5c0-1 1.5-2 3-2s2.5 1 2.5 2"/>
                        </svg>
                        <span class="font-bold text-lg">Sentiment Analyzer</span>
                    </div>
                    <p class="text-gray-600 text-sm">
                        Analisis sentimen untuk memahami tingkat kepuasan pelanggan berdasarkan feedback.
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Produk</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Fitur</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Harga</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Testimonial</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">FAQ</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Perusahaan</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Tentang Kami</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Blog</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Karir</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Kontak</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Legal</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Kebijakan Privasi</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Kebijakan Cookie</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Keamanan</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-sm text-gray-600 mb-4 md:mb-0">
                        &copy; {{ date('Y') }} Sentiment Analyzer. All rights reserved.
                    </p>
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-600 hover:text-gray-900">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-600 hover:text-gray-900">
                            <span class="sr-only">Instagram</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-600 hover:text-gray-900">
                            <span class="sr-only">Twitter</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-600 hover:text-gray-900">
                            <span class="sr-only">GitHub</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>