<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Customer - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f0f2f5; }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center px-4 py-12 bg-slate-100">

    <!-- Header Branding -->
    <div class="flex flex-col items-center mb-8">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-md">AH</div>
            <div>
                <div class="font-extrabold text-slate-800 text-lg leading-tight">AmikomEventHub</div>
                <div class="text-[10px] text-slate-400 leading-none">amikom</div>
            </div>
            <span class="ml-2 px-3 py-1 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-full text-[10px] font-bold flex items-center gap-1">
                <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full inline-block"></span> Akun Customer
            </span>
        </div>
    </div>

    <!-- Card -->
    <div class="w-full max-w-md">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Login Customer</h1>
            <p class="text-sm text-slate-500 mt-1">Masuk untuk memesan tiket event favorit Anda.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
            @if($errors->any())
                <div class="bg-red-50 border border-red-100 text-red-600 text-xs font-medium p-3 rounded-xl mb-6">
                    {{ $errors->first() }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-100 text-red-600 text-xs font-medium p-3 rounded-xl mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('user.login.submit') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        placeholder="email@example.com"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Password</label>
                    <input type="password" name="password" required
                        placeholder="••••••••"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition">
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl font-bold text-sm transition shadow-md shadow-indigo-200 mt-2">
                    Masuk ke Akun Customer
                </button>
            </form>

            <div class="relative my-5">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200"></div></div>
                <div class="relative flex justify-center text-xs uppercase"><span class="bg-white px-3 text-slate-400 font-medium">atau</span></div>
            </div>

            <a href="{{ route('auth.google') }}"
                class="w-full flex items-center justify-center gap-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 py-3 rounded-xl font-bold text-sm transition shadow-sm">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17z"/>
                    <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.29v3.15C3.26 21.3 7.35 24 12 24z"/>
                    <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.29C.47 8.21 0 10.05 0 12s.47 3.79 1.29 5.42l3.99-3.15z"/>
                    <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.35 0 3.26 2.7 1.29 6.58l3.99 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                </svg>
                Masuk dengan Google
            </a>

            <div class="mt-5 text-center">
                <p class="text-xs text-slate-400">Belum punya akun?
                    <a href="{{ route('user.register') }}" class="text-indigo-600 font-semibold hover:underline">Daftar sekarang</a>
                </p>
            </div>
        </div>

        <!-- Switch Role -->
        <div class="mt-6 text-center">
            <p class="text-xs text-slate-400 mb-3">Butuh akses peran lain?</p>
            <div class="flex items-center justify-center gap-4 text-xs font-semibold">
                <a href="{{ url('/partner/login') }}" class="flex items-center gap-1.5 text-emerald-500 hover:text-emerald-700 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Login Partner
                </a>
                <span class="text-slate-300">•</span>
                <a href="{{ route('admin.login') }}" class="flex items-center gap-1.5 text-slate-500 hover:text-slate-700 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Login Admin
                </a>
            </div>
            <div class="mt-4">
                <a href="{{ url('/') }}" class="text-xs text-slate-400 hover:text-slate-600 transition">← Kembali ke Beranda</a>
            </div>
        </div>
    </div>

</body>
</html>
