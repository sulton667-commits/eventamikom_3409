<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AmikomEventHub - Temukan & Pesan Tiket Event Impianmu!</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="theme-color" content="#4f46e5">
    <link rel="manifest" href="/manifest.json">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col justify-between">

    <!-- Header Navigation -->
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
        <nav class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-md shadow-indigo-200 group-hover:scale-105 transition-transform">
                    AH
                </div>
                <span class="text-xl font-extrabold tracking-tight text-slate-900">AmikomEventHub</span>
            </a>

            <div class="hidden md:flex items-center gap-8 font-medium text-sm text-slate-600">
                <a href="{{ url('/') }}" class="hover:text-indigo-600 transition {{ Request::is('/') ? 'text-indigo-600 font-semibold' : '' }}">Jelajahi</a>
                <a href="{{ url('/katalog') }}" class="hover:text-indigo-600 transition {{ Request::is('katalog') ? 'text-indigo-600 font-semibold' : '' }}">Katalog</a>
                <a href="{{ url('/ticket') }}" class="hover:text-indigo-600 transition {{ Request::is('ticket') ? 'text-indigo-600 font-semibold' : '' }}">Tiket Saya</a>
                <a href="{{ url('/profil') }}" class="hover:text-indigo-600 transition {{ Request::is('profil') ? 'text-indigo-600 font-semibold' : '' }}">Profil</a>
                <a href="{{ url('/bantuan') }}" class="hover:text-indigo-600 transition {{ Request::is('bantuan') ? 'text-indigo-600 font-semibold' : '' }}">Bantuan</a>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    <span class="text-sm font-semibold text-slate-700 hidden md:block">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('user.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-5 py-2.5 rounded-xl font-semibold text-slate-700 hover:bg-slate-100 transition text-sm">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('user.login') }}" class="px-5 py-2.5 rounded-xl font-semibold text-slate-700 hover:bg-slate-100 transition text-sm">Login</a>
                    <a href="{{ route('user.register') }}" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition text-sm">Daftar</a>
                @endauth
            </div>
        </nav>
    </header>

    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#1a1c4b] text-indigo-100 py-16 px-6 mt-20">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-900 font-bold text-xl shadow-md">
                        AH
                    </div>
                    <span class="text-2xl font-bold text-white tracking-tight">AmikomEventHub</span>
                </div>
                <p class="text-indigo-200/80 text-sm leading-relaxed max-w-xs">
                    Platform reservasi tiket event online terbaik untuk mahasiswa dan penyelenggara profesional.
                </p>
            </div>

            <div>
                <h4 class="text-white font-bold mb-5 text-base">Kategori</h4>
                <ul class="space-y-3 text-sm text-indigo-200/80">
                    <li><a href="{{ url('/#kategori') }}" class="hover:text-white transition">Entertainment</a></li>
                    <li><a href="{{ url('/#kategori') }}" class="hover:text-white transition">Futsal</a></li>
                    <li><a href="{{ url('/#kategori') }}" class="hover:text-white transition">Musik</a></li>
                    <li><a href="{{ url('/#kategori') }}" class="hover:text-white transition">Seminar IT</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold mb-5 text-base">Navigasi Akses</h4>
                <ul class="space-y-3 text-sm text-indigo-200/80">
                    <li><a href="{{ url('/') }}" class="hover:text-white transition">Beranda</a></li>
                    <li><a href="{{ url('/katalog') }}" class="hover:text-white transition">Katalog</a></li>
                    <li><a href="{{ url('/ticket') }}" class="hover:text-white transition">Tiket Saya</a></li>
                    <li><a href="{{ url('/profil') }}" class="hover:text-white transition">Profil</a></li>
                    <li><a href="{{ url('/bantuan') }}" class="hover:text-white transition">Bantuan</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold mb-5 text-base">Hubungi Kami</h4>
                <ul class="space-y-3 text-sm text-indigo-200/80">
                    <li>support@eventtiket.com</li>
                    <li>+62 812 3456 7890</li>
                </ul>
            </div>
        </div>

        <div class="max-w-7xl mx-auto pt-8 mt-12 border-t border-indigo-900/60 text-center text-indigo-300/60 text-xs tracking-wider uppercase">
            &copy; 2026 AMIKOMEVENTHUB. HAK CIPTA DILINDUNGI.
        </div>
    </footer>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').then(registration => {
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }).catch(err => {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>
</body>
</html>