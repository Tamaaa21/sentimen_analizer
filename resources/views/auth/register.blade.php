<!-- resources/views/auth/register.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sentiment Analyzer</title>
    <meta name="description" content="Register to access Sentiment Analyzer">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
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
    </style>
</head>
<body class="animated-gradient min-h-screen flex items-center justify-center p-4">
    <div class="glass-card rounded-xl overflow-hidden shadow-xl w-full max-w-md">
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-6">
            <div class="flex items-center gap-2 justify-center mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M9.082 16.007c.36.18.711.327 1.082.429m3.754-1.422c.36.18.711.327 1.082.429M9.082 7.007c.36-.18.711-.327 1.082-.429m4.836 1.85a5.5 5.5 0 0 1-1.082-.429"/>
                    <path d="M7 16.5c0-1 1.5-2 3-2s2.5 1 2.5 2m2-6.5c0-1 1.5-2 3-2s2.5 1 2.5 2"/>
                </svg>
                <h1 class="text-2xl font-bold">Sentiment Analyzer</h1>
            </div>
            <p class="text-center text-white/80">Daftar untuk mengakses aplikasi</p>
        </div>
        <div class="p-6">
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus class="w-full rounded-md border-2 border-indigo-100 focus:border-indigo-300 p-3 transition-all" placeholder="Nama Lengkap">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required class="w-full rounded-md border-2 border-indigo-100 focus:border-indigo-300 p-3 transition-all" placeholder="nama@example.com">
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" required class="w-full rounded-md border-2 border-indigo-100 focus:border-indigo-300 p-3 transition-all" placeholder="••••••••">
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full rounded-md border-2 border-indigo-100 focus:border-indigo-300 p-3 transition-all" placeholder="••••••••">
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white px-4 py-3 rounded-md transition-all duration-300 shadow-md font-medium">
                    Daftar
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Login sekarang
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>