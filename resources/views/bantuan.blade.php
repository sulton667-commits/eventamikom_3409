@extends('layouts.app')

@section('content')
<section class="max-w-3xl mx-auto px-6 py-16">
    <div class="bg-white p-8 md:p-12 rounded-3xl shadow-sm border border-slate-100">
        <h1 class="text-2xl font-bold text-slate-900 mb-6 text-center">Pusat Bantuan & FAQ</h1>
        
        <div class="space-y-6 text-slate-700">
            <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100">
                <h3 class="font-bold text-slate-900 text-sm mb-1">Q: Bagaimana cara memesan tiket event?</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Pilih event yang diinginkan di beranda atau katalog, klik tombol "Lihat Detail", kemudian ikuti petunjuk checkout pembayaran.</p>
            </div>
            
            <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100">
                <h3 class="font-bold text-slate-900 text-sm mb-1">Q: Metode pembayaran apa saja yang didukung?</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Pembayaran didukung secara otomatis melalui gateway Midtrans (Virtual Account, E-Wallet, QRIS, dll).</p>
            </div>

            <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100">
                <h3 class="font-bold text-slate-900 text-sm mb-1">Q: Di mana saya bisa melihat tiket yang sudah dibeli?</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Anda dapat mengakses tiket pada menu "Tiket Saya" yang terdapat di navigasi atas.</p>
            </div>
        </div>

        <div class="flex justify-center gap-3 pt-8 mt-8 border-t border-slate-100">
            <a href="{{ url('/') }}" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-200 hover:bg-indigo-700 transition">Kembali ke Beranda</a>
        </div>
    </div>
</section>
@endsection