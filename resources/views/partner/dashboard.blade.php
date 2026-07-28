@extends('layouts.app')

@section('content')
<section class="max-w-2xl mx-auto px-6 py-16 text-center">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-10">
        <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center font-bold text-2xl mx-auto mb-4">P</div>
        <h1 class="text-2xl font-bold text-slate-900 mb-2">Dashboard Partner</h1>
        <p class="text-slate-500 text-sm mb-6">Selamat datang, <strong>{{ Auth::user()->name }}</strong>! Anda berhasil masuk sebagai Partner.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8 text-left">
            <div class="bg-emerald-50 p-5 rounded-2xl border border-emerald-100">
                <p class="text-xs font-bold text-emerald-700 uppercase mb-1">Email Akun</p>
                <p class="font-semibold text-slate-800">{{ Auth::user()->email }}</p>
            </div>
            <div class="bg-indigo-50 p-5 rounded-2xl border border-indigo-100">
                <p class="text-xs font-bold text-indigo-700 uppercase mb-1">Role</p>
                <p class="font-semibold text-slate-800 capitalize">{{ Auth::user()->role }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('user.logout') }}" class="mt-8">
            @csrf
            <button type="submit" class="px-6 py-2.5 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                Keluar
            </button>
        </form>
    </div>
</section>
@endsection
