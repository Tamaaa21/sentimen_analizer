<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentiment Analyzer - @yield('title', 'Prediksi Kepuasan Pelanggan')</title>
    <meta name="description" content="Analisis sentimen untuk memahami tingkat kepuasan pelanggan berdasarkan feedback">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        dark: {
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --header-height: 70px;
        }

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
        
        .header-gradient {
            background: linear-gradient(90deg, #00c0ff 0%, #00c0ff 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .sidebar {
            width: 280px;
            height: calc(100vh - var(--header-height));
            background:linear-gradient(90deg, #00c0ff 0%, #00c0ff 100%);
        }

        .main-content {
            margin-left: 280px;
            min-height: calc(100vh - var(--header-height));
        }

        .active-menu {
            background: rgba(99, 102, 241, 0.15);
            border-left: 4px solid #6366f1;
            color: #e0f2fe !important;
        }

        .active-menu i {
            color: #6366f1 !important;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
        }

        .user-avatar {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        }

        .nav-tabs .nav-link {
            border: none;
            color: #64748b;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            position: relative;
        }

        .nav-tabs .nav-link.active {
            color: #0ea5e9;
        }

        .nav-tabs .nav-link.active:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: #0ea5e9;
        }
    </style>
</head>
<body class= "grey bg-gry-50">
    <!-- Header Full Width -->
    <header class="header-gradient sticky top-0 z-40 w-full text-white">
        <div class="flex h-[var(--header-height)] items-center justify-between px-6">
            <div class="flex items-center gap-4">
                <button id="mobile-sidebar-toggle" class="md:hidden text-gray-300 hover:text-white">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-indigo-500">
                        <i class="fas fa-comment-dots text-white text-lg"></i>
                    </div>
                    <span class="font-bold text-xl hidden sm:inline-block">Sentiment Analyzer</span>
                </div>
            </div>
            <nav class="flex items-center gap-4">
                <a href="https://github.com/Tamaaa21/sentimen_analizer" target="_blank" rel="noopener noreferrer" class="p-2 rounded-full hover:bg-slate-700 transition-colors text-gray-300 hover:text-white">
                    <i class="fab fa-github text-xl"></i>
                    <span class="sr-only">GitHub</span>
                </a>
                
                <!-- User Dropdown -->
                <div class="relative">
                    <button type="button" class="flex items-center gap-x-3" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                        <div class="user-avatar h-9 w-9 rounded-full flex items-center justify-center text-white font-medium shadow-md">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="hidden md:flex flex-col items-start">
                            <span class="text-sm font-medium text-white">{{ Auth::user()->name }}</span>
                            <span class="text-xs text-gray-300">Aktif</span>
                        </div>
                        <i class="fas fa-chevron-down text-gray-300 text-xs hidden md:inline-block"></i>
                    </button>
                    <div id="user-dropdown" class="hidden absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-sm text-gray-700">Signed in as</p>
                            <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </nav>
        </div>

       
    </header>

    <div class="flex">
        <!-- Sidebar (hidden on mobile) -->
        <aside class="sidebar fixed top-0 left-0 z-40 mt-[var(--header-height)] bg-white shadow-lg border-r border-gray-200">
            <div class="h-full overflow-y-auto py-4 px-3">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('sentiments.index') }}" class="flex items-center p-3 text-base font-medium text-gray-700 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('sentiments.index') ? 'active-menu' : '' }}">
                            <i class="fas fa-comment-alt text-gray-500 group-hover:text-indigo-600 transition-colors {{ request()->routeIs('sentiments.index') ? 'text-indigo-600' : '' }}"></i>
                            <span class="ml-3">Input Sentimen</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('sentiments.dashboard') }}" class="flex items-center p-3 text-base font-medium text-gray-700 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('sentiments.dashboard') ? 'active-menu' : '' }}">
                            <i class="fas fa-chart-bar text-gray-500 group-hover:text-indigo-600 transition-colors {{ request()->routeIs('sentiments.dashboard') ? 'text-indigo-600' : '' }}"></i>
                            <span class="ml-3">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('sentiments.history') }}" class="flex items-center p-3 text-base font-medium text-gray-700 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('sentiments.history') ? 'active-menu' : '' }}">
                            <i class="fas fa-history text-gray-500 group-hover:text-indigo-600 transition-colors {{ request()->routeIs('sentiments.history') ? 'text-indigo-600' : '' }}"></i>
                            <span class="ml-3">Riwayat Analisis</span>
                        </a>
                    </li>
                </ul>
                
                <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <button id="sidebar-toggle" class="text-gray-500 hover:text-indigo-600 transition-colors">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content w-full bg-gradient-to-br from-gray-50 to-gray-100">
            <div class="py-8 px-6">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Footer -->
    <!-- <footer class="border-t bg-white">
        <div class="container mx-auto flex flex-col items-center justify-between gap-4 py-6 md:h-20 md:flex-row md:py-0">
            <div class="flex flex-col items-center gap-4 px-8 md:flex-row md:gap-3 md:px-0">
                <div class="flex items-center gap-3">
                    <div class="h-6 w-6 rounded-lg bg-indigo-500 flex items-center justify-center">
                        <i class="fas fa-comment-dots text-white text-xs"></i>
                    </div>
                    <p class="text-center text-sm text-gray-600 md:text-left">
                        &copy; {{ date('Y') }} Sentiment Analyzer. All rights reserved.
                    </p>
                </div>
            </div>
            <div class="flex gap-4">
                <a href="#" class="text-sm font-medium text-gray-600 hover:text-indigo-600">Terms</a>
                <a href="#" class="text-sm font-medium text-gray-600 hover:text-indigo-600">Privacy</a>
                <a href="#" class="text-sm font-medium text-gray-600 hover:text-indigo-600">About</a>
            </div>
        </div>
    </footer> -->

    <script>
        // Toggle user dropdown
        const userMenuButton = document.getElementById('user-menu-button');
        const userDropdown = document.getElementById('user-dropdown');

        userMenuButton.addEventListener('click', () => {
            userDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.classList.add('hidden');
            }
        });

        // Mobile sidebar toggle
        const mobileSidebarToggle = document.getElementById('mobile-sidebar-toggle');
        const sidebar = document.querySelector('aside');

        mobileSidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('hidden');
        });
    </script>

    @stack('scripts')
</body>
</html>