<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Partner - AmikomEventHub</title>
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
            <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-md">AH</div>
            <div>
                <div class="font-extrabold text-slate-800 text-lg leading-tight">AmikomEventHub</div>
                <div class="text-[10px] text-slate-400 leading-none">amikom</div>
            </div>
            <span class="ml-2 px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-full text-[10px] font-bold flex items-center gap-1">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full inline-block"></span> Panel Partner & Panitia
            </span>
        </div>
    </div>

    <!-- Card -->
    <div class="w-full max-w-md">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Login Partner</h1>
            <p class="text-sm text-slate-500 mt-1">Masuk ke dashboard kepanitiaan Anda.</p>
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

            <form action="{{ route('partner.login.submit') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Partner</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        placeholder="panitia@email.com"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Password</label>
                    <input type="password" name="password" required
                        placeholder="••••••••"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                </div>

                <button type="submit"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3 rounded-xl font-bold text-sm transition shadow-md shadow-emerald-200 mt-2">
                    Masuk ke Dashboard Partner
                </button>
            </form>

            <div class="mt-5 text-center">
                <p class="text-xs text-slate-400">Belum punya akun partner?
                    <a href="{{ route('partner.register') }}" class="text-emerald-600 font-semibold hover:underline">Daftar Partner Baru</a>
                </p>
            </div>
        </div>

        <!-- Switch Role -->
        <div class="mt-6 text-center">
            <p class="text-xs text-slate-400 mb-3">Butuh akses peran lain?</p>
            <div class="flex items-center justify-center gap-4 text-xs font-semibold">
                <a href="{{ url('/login') }}" class="flex items-center gap-1.5 text-indigo-500 hover:text-indigo-700 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Login Customer
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
