@extends('layouts.app')

@section('content')
<section class="max-w-2xl mx-auto px-6 py-16">
    <div class="bg-white p-8 md:p-12 rounded-3xl shadow-sm border border-slate-100 text-center">
        <div class="w-20 h-20 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold text-2xl mx-auto mb-6">
            MS
        </div>
        <h1 class="text-2xl font-bold text-slate-900 mb-2">Profil Praktikum Digital Bisnis</h1>
        <p class="text-slate-600 font-medium">Nama: Muhamad Sulthon</p>
        <p class="text-slate-600 font-medium">NIM: 24.12.3409</p>
        <p class="text-slate-500 text-sm mt-1 mb-8">Program Studi: S1 Sistem Informasi</p>

        <div class="flex flex-wrap justify-center gap-3 pt-4 border-t border-slate-100">
            <a href="{{ url('/') }}" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold shadow-md shadow-indigo-200 hover:bg-indigo-700 transition">Beranda</a>
            <a href="{{ url('/katalog') }}" class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl text-sm font-semibold hover:bg-slate-200 transition">Katalog</a>
            <a href="{{ url('/bantuan') }}" class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl text-sm font-semibold hover:bg-slate-200 transition">Bantuan</a>
        </div>
    </div>
</section>
@endsection