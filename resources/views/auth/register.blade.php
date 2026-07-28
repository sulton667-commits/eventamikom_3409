<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - AmikomEventHub</title>
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
        </div>
    </div>

    <!-- Card -->
    <div class="w-full max-w-md">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Buat Akun Baru</h1>
            <p class="text-sm text-slate-500 mt-1">Daftar untuk mulai memesan tiket event.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
            @if($errors->any())
                <div class="bg-red-50 border border-red-100 text-red-600 text-xs font-medium p-3 rounded-xl mb-6">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('user.register.submit') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        placeholder="Nama Anda"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        placeholder="email@example.com"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Password</label>
                    <input type="password" name="password" required
                        placeholder="Minimal 6 karakter"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required
                        placeholder="Ulangi password"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition">
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl font-bold text-sm transition shadow-md shadow-indigo-200 mt-2">
                    Buat Akun
                </button>
            </form>

            <div class="mt-5 text-center">
                <p class="text-xs text-slate-400">Sudah punya akun?
                    <a href="{{ route('user.login') }}" class="text-indigo-600 font-semibold hover:underline">Login di sini</a>
                </p>
            </div>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ url('/') }}" class="text-xs text-slate-400 hover:text-slate-600 transition">← Kembali ke Beranda</a>
        </div>
    </div>

</body>
</html>
