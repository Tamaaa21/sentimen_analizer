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
                    },
                    borderRadius: {
                        'lg': '0.5rem',
                        'md': 'calc(0.5rem - 2px)',
                        'sm': 'calc(0.5rem - 4px)',
                    },
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
            --header-height: 64px;
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

        .bg-gradient-primary {
            background: linear-gradient(to right, #0ea5e9, #0284c7);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .sidebar {
            width: 256px;
            height: calc(100vh - var(--header-height));
            transition: width 0.3s ease;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .main-content {
            margin-left: 256px;
            min-height: calc(100vh - var(--header-height));
            transition: margin-left 0.3s ease;
        }

        .main-content.sidebar-collapsed {
            margin-left: 80px;
        }

        .active-menu {
            background-color: rgba(219, 234, 254, 1);
            color: #0284c7 !important;
            border-left: 4px solid #0284c7;
        }

        .active-menu i {
            color: #0284c7 !important;
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

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 50;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .main-content.sidebar-collapsed {
                margin-left: 0;
            }
        }
    </style>
</head>
<body class="animated-gradient from-gray-50 to-gray-100">
    <!-- Header -->
    <header class="bg-gradient-primary sticky top-0 z-40 w-full shadow-md">
        <div class="flex h-[var(--header-height)] items-center justify-between px-4 md:px-6">
            <div class="flex items-center gap-4">
                <button id="mobile-sidebar-toggle" class="md:hidden text-white hover:bg-white/10 p-2 rounded-full">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20 backdrop-blur-sm">
                        <i class="fas fa-comment-dots text-white text-lg"></i>
                    </div>
                    <span class="hidden font-bold text-xl text-white sm:inline-block">Sentiment Analyzer</span>
                </div>
            </div>
            <nav class="flex items-center gap-4">
                <a href="https://github.com/Tamaaa21/sentimen_analizer" target="_blank" rel="noopener noreferrer" class="p-2 rounded-full hover:bg-white/10 transition-colors text-white">
                    <i class="fab fa-github text-xl"></i>
                    <span class="sr-only">GitHub</span>
                </a>
                
                <!-- User Dropdown -->
                <div class="relative" id="user-menu-container">
                    <button type="button" class="flex items-center gap-x-3" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                        <div class="user-avatar h-9 w-9 rounded-full flex items-center justify-center text-white font-medium shadow-md">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="hidden md:flex flex-col items-start">
                            <span class="text-sm font-medium text-white">{{ Auth::user()->name }}</span>
                            <span class="text-xs text-white/70">Aktif</span>
                        </div>
                        <i class="fas fa-chevron-down text-white/70 text-xs hidden md:inline-block"></i>
                    </button>
                    <div id="user-dropdown" class="hidden absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
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
        <!-- Sidebar -->
        <aside class="sidebar fixed top-[var(--header-height)] left-0 z-30 bg-white shadow-lg border-r border-gray-200" id="sidebar">
            <div class="h-full overflow-y-auto py-4 px-3">
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('sentiments.index') }}" class="flex items-center rounded-lg px-3 py-3 text-gray-700 hover:bg-gray-100 transition-colors {{ request()->routeIs('sentiments.index') ? 'active-menu' : '' }}">
                            <i class="fas fa-comment text-gray-500 transition-colors {{ request()->routeIs('sentiments.index') ? 'text-blue-600' : '' }}"></i>
                            <span class="ml-3 text-sm font-medium sidebar-text">Input Sentimen</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('sentiments.dashboard') }}" class="flex items-center rounded-lg px-3 py-3 text-gray-700 hover:bg-gray-100 transition-colors {{ request()->routeIs('sentiments.dashboard') ? 'active-menu' : '' }}">
                            <i class="fas fa-chart-bar text-gray-500 transition-colors {{ request()->routeIs('sentiments.dashboard') ? 'text-blue-600' : '' }}"></i>
                            <span class="ml-3 text-sm font-medium sidebar-text">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('sentiments.history') }}" class="flex items-center rounded-lg px-3 py-3 text-gray-700 hover:bg-gray-100 transition-colors {{ request()->routeIs('sentiments.history') ? 'active-menu' : '' }}">
                            <i class="fas fa-history text-gray-500 transition-colors {{ request()->routeIs('sentiments.history') ? 'text-blue-600' : '' }}"></i>
                            <span class="ml-3 text-sm font-medium sidebar-text">Riwayat Analisis</span>
                        </a>
                    </li>
                </ul>
                
                <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200">
                    <button id="sidebar-toggle" class="flex w-full items-center justify-center rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-blue-600 transition-colors">
                        <i class="fas fa-chevron-left transition-transform duration-200" id="sidebar-toggle-icon"></i>
                        <span class="ml-2 text-sm font-medium sidebar-text">Collapse</span>
                    </button>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content animated-gradient id="main-content">
            <div class="container mx-auto p-6">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden md:hidden"></div>

    <script>
        // Toggle user dropdown
        const userMenuButton = document.getElementById('user-menu-button');
        const userDropdown = document.getElementById('user-dropdown');

        userMenuButton.addEventListener('click', () => {
            userDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            const userMenuContainer = document.getElementById('user-menu-container');
            if (userMenuContainer && !userMenuContainer.contains(e.target)) {
                userDropdown.classList.add('hidden');
            }
        });

        // Mobile sidebar toggle
        const mobileSidebarToggle = document.getElementById('mobile-sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        mobileSidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('mobile-open');
            sidebarOverlay.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        });

        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('mobile-open');
            sidebarOverlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });

        // Sidebar collapse toggle
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarToggleIcon = document.getElementById('sidebar-toggle-icon');
        const mainContent = document.getElementById('main-content');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('sidebar-collapsed');
            sidebarToggleIcon.classList.toggle('rotate-180');
            
            sidebarTexts.forEach(text => {
                text.classList.toggle('hidden');
            });
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('mobile-open');
                sidebarOverlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>