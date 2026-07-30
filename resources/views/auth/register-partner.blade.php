<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Partner - AmikomEventHub</title>
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
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full inline-block"></span> Pendaftaran Partner & Panitia
            </span>
        </div>
    </div>

    <!-- Card -->
    <div class="w-full max-w-lg">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Daftar Akun Partner</h1>
            <p class="text-sm text-slate-500 mt-1">Buat akun untuk mempublikasikan dan mengelola event Anda.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
            @if($errors->any())
                <div class="bg-red-50 border border-red-100 text-red-600 text-xs font-medium p-3 rounded-xl mb-6">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('partner.register.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Partner / Organisasi</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        placeholder="Contoh: BEM Amikom / HMTI"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Partner</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        placeholder="organisasi@email.com"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kategori Penyelenggara</label>
                    <select name="category" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition text-slate-700">
                        <option value="" disabled {{ old('category') ? '' : 'selected' }}>Pilih Kategori</option>
                        <option value="UKM / Ormawa" {{ old('category') == 'UKM / Ormawa' ? 'selected' : '' }}>UKM / Ormawa</option>
                        <option value="Himpunan / Prodi" {{ old('category') == 'Himpunan / Prodi' ? 'selected' : '' }}>Himpunan / Prodi</option>
                        <option value="Komunitas" {{ old('category') == 'Komunitas' ? 'selected' : '' }}>Komunitas</option>
                        <option value="Instansi / Perusahaan" {{ old('category') == 'Instansi / Perusahaan' ? 'selected' : '' }}>Instansi / Perusahaan</option>
                        <option value="Penyelenggara Umum" {{ old('category') == 'Penyelenggara Umum' ? 'selected' : '' }}>Penyelenggara Umum</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Website / Media Sosial (Opsional)</label>
                    <input type="url" name="website_url" value="{{ old('website_url') }}"
                        placeholder="https://instagram.com/organisasi_anda"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Password</label>
                    <input type="password" name="password" required
                        placeholder="Minimal 6 karakter"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required
                        placeholder="Ulangi password"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                </div>

                <button type="submit"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3.5 rounded-xl font-bold text-sm transition shadow-md shadow-emerald-200 mt-4">
                    Daftar Akun Partner
                </button>
            </form>

            <div class="mt-5 text-center">
                <p class="text-xs text-slate-400">Sudah punya akun partner?
                    <a href="{{ route('partner.login') }}" class="text-emerald-600 font-semibold hover:underline">Masuk di sini</a>
                </p>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ url('/') }}" class="text-xs text-slate-400 hover:text-slate-600 transition">← Kembali ke Beranda</a>
        </div>
    </div>

</body>
</html>
