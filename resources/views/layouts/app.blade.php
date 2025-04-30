<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentiment Analyzer - @yield('title', 'Prediksi Kepuasan Pelanggan')</title>
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

        .glow-positive {
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.5);
        }

        .glow-neutral {
            box-shadow: 0 0 15px rgba(245, 158, 11, 0.5);
        }

        .glow-negative {
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.5);
        }

        @media print {
            @page {
                size: A4;
                margin: 1cm;
            }

            body {
                background: white !important;
                color: black !important;
            }

            .print-hidden {
                display: none !important;
            }

            .print-break-inside-avoid {
                break-inside: avoid;
            }

            .print-break-before-page {
                break-before: page;
            }
        }
    </style>
</head>
<body>
    <div class="flex min-h-screen flex-col">
        <!-- Header -->
        <header class="sticky top-0 z-50 w-full border-b bg-white/95 backdrop-blur">
            <div class="container mx-auto flex h-16 items-center justify-between px-4">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 text-blue-600">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9.082 16.007c.36.18.711.327 1.082.429m3.754-1.422c.36.18.711.327 1.082.429M9.082 7.007c.36-.18.711-.327 1.082-.429m4.836 1.85a5.5 5.5 0 0 1-1.082-.429"/>
                        <path d="M7 16.5c0-1 1.5-2 3-2s2.5 1 2.5 2m2-6.5c0-1 1.5-2 3-2s2.5 1 2.5 2"/>
                    </svg>
                    <a href="{{ route('sentiments.index') }}" class="flex items-center gap-1">
                        <span class="font-bold text-lg hidden sm:inline-block">Sentiment Analyzer</span>
                    </a>
                </div>
                <nav class="flex items-center gap-2">
                    <a href="https://github.com/yourusername/sentiment-analyzer" target="_blank" rel="noopener noreferrer" class="p-2 rounded-full hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.403 5.403 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4"/>
                            <path d="M9 18c-4.51 2-5-2-7-2"/>
                        </svg>
                        <span class="sr-only">GitHub</span>
                    </a>
                    <a href="{{ route('sentiments.dashboard') }}" class="p-2 rounded-full hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <path d="M3 9h18"/>
                            <path d="M9 21V9"/>
                        </svg>
                        <span class="sr-only">Dashboard</span>
                    </a>
                    
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
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 animated-gradient py-10 px-4">
            <div class="container mx-auto max-w-6xl">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="border-t bg-white">
            <div class="container mx-auto flex flex-col items-center justify-between gap-4 py-10 md:h-24 md:flex-row md:py-0">
                <div class="flex flex-col items-center gap-4 px-8 md:flex-row md:gap-2 md:px-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 text-blue-600">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9.082 16.007c.36.18.711.327 1.082.429m3.754-1.422c.36.18.711.327 1.082.429M9.082 7.007c.36-.18.711-.327 1.082-.429m4.836 1.85a5.5 5.5 0 0 1-1.082-.429"/>
                        <path d="M7 16.5c0-1 1.5-2 3-2s2.5 1 2.5 2m2-6.5c0-1 1.5-2 3-2s2.5 1 2.5 2"/>
                    </svg>
                    <p class="text-center text-sm leading-loose md:text-left">
                        &copy; {{ date('Y') }} Sentiment Analyzer. All rights reserved.
                    </p>
                </div>
                <div class="flex gap-4">
                    <a href="#" class="text-sm font-medium underline underline-offset-4">Terms</a>
                    <a href="#" class="text-sm font-medium underline underline-offset-4">Privacy</a>
                    <a href="#" class="text-sm font-medium underline underline-offset-4">About</a>
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>