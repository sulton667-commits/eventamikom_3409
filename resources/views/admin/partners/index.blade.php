@extends('layouts.admin')

@section('page_title', 'Kelola Partner')
@section('page_subtitle', 'Daftar partner dan mitra terintegrasi dengan platform')

@section('content')
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-8 border-b flex flex-col sm:flex-row justify-between items-center gap-4">
        <div>
            <h3 class="font-black text-xl text-slate-900">Daftar Partner</h3>
            <p class="text-xs text-slate-400 mt-1">Kelola kolaborasi dan mitra resmi</p>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <form method="GET" action="{{ route('admin.partners.index') }}" class="flex-1 sm:w-64">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama / kategori..." class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-indigo-600">
            </form>
            <a href="{{ route('admin.partners.create') }}" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-200 hover:bg-indigo-700 transition whitespace-nowrap">
                + Tambah Partner
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4">Nama Partner</th>
                    <th class="px-8 py-4">Kategori Partner</th>
                    <th class="px-8 py-4">Website</th>
                    <th class="px-8 py-4">Status</th>
                    <th class="px-8 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y border-t">
                @forelse($partners as $partner)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-8 py-6 flex items-center gap-4">
                            @if($partner->logo_path && str_starts_with($partner->logo_path, 'http'))
                                <img src="{{ $partner->logo_path }}" alt="{{ $partner->name }}" class="w-10 h-10 object-cover rounded-xl shadow-sm border border-slate-100">
                            @else
                                <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-bold text-sm shadow-sm">
                                    {{ strtoupper(substr($partner->name, 0, 2)) }}
                                </div>
                            @endif

                            <div>
                                <p class="font-bold text-sm text-slate-900">{{ $partner->name }}</p>
                                <p class="text-xs text-slate-400">ID: PTR-00{{ $partner->id }}</p>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-sm font-medium text-slate-600">
                            {{ $partner->category }}
                        </td>
                        <td class="px-8 py-6 text-xs text-slate-500">
                            @if($partner->website_url)
                                <a href="{{ $partner->website_url }}" target="_blank" class="text-indigo-600 hover:underline">
                                    {{ parse_url($partner->website_url, PHP_URL_HOST) ?? $partner->website_url }}
                                </a>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 {{ strtolower($partner->status) == 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }} rounded-lg text-xs font-bold uppercase">
                                {{ $partner->status }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.partners.edit', $partner->id) }}" class="px-3 py-1.5 bg-amber-50 text-amber-600 rounded-lg text-xs font-bold hover:bg-amber-100 transition">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.partners.destroy', $partner->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus partner ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-600 rounded-lg text-xs font-bold hover:bg-rose-100 transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center text-slate-400 text-sm">
                            Belum ada partner terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($partners->hasPages())
        <div class="p-6 border-t">
            {{ $partners->links() }}
        </div>
    @endif
</div>
@endsection
