@extends('layouts.admin')

@section('page_title', 'Tambah Admin Baru')
@section('page_subtitle', 'Buat akun administrator baru untuk mengakses panel kelola')

@section('content')
<div class="max-w-2xl bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
        @csrf

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Administrator Amikom" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-600">
            @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Alamat Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required placeholder="admin@amikom.ac.id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-600">
            @error('email') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Role</label>
            <select name="role" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-600">
                <option value="admin">Admin</option>
                <option value="organizer">Organizer</option>
            </select>
            @error('role') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Password</label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-600">
                @error('password') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required placeholder="Ulangi password" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-600">
            </div>
        </div>

        <div class="flex items-center gap-3 pt-4 border-t">
            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-200 hover:bg-indigo-700 transition">
                Simpan Admin
            </button>
            <a href="{{ route('admin.users.index') }}" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
