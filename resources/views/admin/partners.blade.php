@extends('layouts.admin')

@section('page_title', 'Kelola Partner')
@section('page_subtitle', 'Daftar partner dan mitra terintegrasi dengan platform')

@section('content')
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-8 border-b flex justify-between items-center">
        <div>
            <h3 class="font-black text-xl text-slate-900">Daftar Partner</h3>
            <p class="text-xs text-slate-400 mt-1">Kelola kolaborasi dan mitra pembayaran</p>
        </div>
        <button class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-200 hover:bg-indigo-700 transition">
            + Tambah Partner
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4">Nama Partner</th>
                    <th class="px-8 py-4">Kategori Partner</th>
                    <th class="px-8 py-4">Status</th>
                    <th class="px-8 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y border-t">
                @foreach($partners as $partner)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-8 py-6 flex items-center gap-4">
                            <div class="w-10 h-10 {{ $partner['color'] }} rounded-xl flex items-center justify-center font-bold text-sm shadow-sm">
                                {{ $partner['logo'] }}
                            </div>
                            <div>
                                <p class="font-bold text-sm text-slate-900">{{ $partner['name'] }}</p>
                                <p class="text-xs text-slate-400">ID: PTR-00{{ $partner['id'] }}</p>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-sm font-medium text-slate-600">
                            {{ $partner['category'] }}
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold uppercase">
                                {{ $partner['status'] }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <button class="px-3 py-1.5 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-200 transition">
                                Edit
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
