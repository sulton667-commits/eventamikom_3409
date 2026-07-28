@extends('layouts.admin')

@section('page_title', 'Tambah Partner Baru')
@section('page_subtitle', 'Tambahkan informasi mitra atau penyelenggara baru')

@section('content')
<div class="max-w-2xl bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
    <form method="POST" action="{{ route('admin.partners.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Nama Partner</label>
            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Midtrans / Amikom Yogyakarta" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-600">
            @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Kategori Partner</label>
            <input type="text" name="category" value="{{ old('category') }}" required placeholder="Contoh: Media Partner / Payment Gateway / Penyelenggara" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-600">
            @error('category') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">URL Website (Opsional)</label>
            <input type="url" name="website_url" value="{{ old('website_url') }}" placeholder="https://example.com" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-600">
            @error('website_url') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Status</label>
            <select name="status" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-600">
                <option value="Aktif">Aktif</option>
                <option value="Non-Aktif">Non-Aktif</option>
            </select>
            @error('status') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Logo Partner (Opsional)</label>
            <input type="file" name="logo" accept="image/*" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-600">
            @error('logo') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-3 pt-4 border-t">
            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-200 hover:bg-indigo-700 transition">
                Simpan Partner
            </button>
            <a href="{{ route('admin.partners.index') }}" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
