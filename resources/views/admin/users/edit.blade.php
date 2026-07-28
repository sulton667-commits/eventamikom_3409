@extends('layouts.admin')

@section('page_title', 'Edit Data Admin')
@section('page_subtitle', 'Perbarui informasi pengguna admin')

@section('content')
<div class="max-w-2xl bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-600">
            @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Alamat Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-600">
            @error('email') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Role</label>
            <select name="role" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-600">
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="organizer" {{ $user->role == 'organizer' ? 'selected' : '' }}>Organizer</option>
            </select>
            @error('role') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
            <p class="text-xs font-bold text-slate-700 mb-4">Ubah Password (Kosongkan jika tidak ingin mengubah password)</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Password Baru</label>
                    <input type="password" name="password" placeholder="Minimal 6 karakter" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-600">
                    @error('password') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-600">
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 pt-4 border-t">
            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-200 hover:bg-indigo-700 transition">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.users.index') }}" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
