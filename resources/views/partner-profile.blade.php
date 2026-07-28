@extends('layouts.app')

@section('content')
<main class="max-w-7xl mx-auto px-6 py-10 space-y-12">

    <!-- Organizer Header Hero Card -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-[2.5rem] p-8 md:p-12 text-white shadow-2xl relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center gap-8">
            <!-- Logo Box -->
            <div class="w-24 h-24 bg-white rounded-3xl flex items-center justify-center text-indigo-900 font-black text-3xl shadow-lg shrink-0">
                {{ strtoupper(substr($partner->name ?? 'HM', 0, 2)) }}
            </div>

            <!-- Details -->
            <div class="space-y-3 flex-1">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded-full text-[10px] font-extrabold uppercase tracking-wider">
                        ✔ VERIFIED PARTNER
                    </span>
                    <span class="px-3 py-1 bg-indigo-500/20 text-indigo-200 border border-indigo-500/30 rounded-full text-[10px] font-extrabold uppercase tracking-wider">
                        Penyelenggara Resmi Amikom
                    </span>
                </div>
                <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight">{{ $partner->name ?? 'HMSSI Amikom' }}</h1>
                <p class="text-indigo-200 text-sm font-medium">{{ $partner->category ?? 'Himpunan Mahasiswa Sistem & Sains Informasi Amikom' }}</p>
            </div>
        </div>

        <!-- Metric Cards Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-10 pt-8 border-t border-white/10 relative z-10">
            <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-indigo-200 mb-1">RATA-RATA RATING</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-black text-amber-400">{{ number_format($avgRating, 1) }}</span>
                    <span class="text-amber-400 text-xs">★★★★☆</span>
                </div>
                <p class="text-[10px] text-slate-400 mt-0.5">{{ $totalReviews }} ulasan</p>
            </div>

            <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-indigo-200 mb-1">TOTAL ACARA</p>
                <span class="text-2xl font-black text-white">{{ $totalEvents }}</span>
                <span class="text-xs text-indigo-300 font-medium ml-1">Event</span>
            </div>

            <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-indigo-200 mb-1">TIKET TERJUAL</p>
                <span class="text-2xl font-black text-white">{{ $ticketsSold }}</span>
                <span class="text-xs text-indigo-300 font-medium ml-1">Tiket</span>
            </div>

            <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-indigo-200 mb-1">TINGKAT KEPERCAYAAN</p>
                <span class="text-2xl font-black text-emerald-400">100%</span>
                <span class="text-xs text-emerald-200 font-medium ml-1">Terverifikasi</span>
            </div>
        </div>

        <!-- Decoration circles -->
        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-indigo-500/10 rounded-full blur-2xl"></div>
        <div class="absolute -left-10 -top-10 w-40 h-40 bg-purple-500/10 rounded-full blur-xl"></div>
    </div>

    <!-- Main Content 2 Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        <!-- Left Column: Rekam Jejak Ulasan & Rating -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                    <div>
                        <h2 class="text-2xl font-extrabold text-slate-900 flex items-center gap-2">
                            <span>🌟</span> Rekam Jejak Ulasan & Rating
                        </h2>
                        <p class="text-xs text-slate-500 font-medium mt-1">Testimoni asli dari para peserta acara sebelumnya.</p>
                    </div>
                    <div class="px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-2xl text-center">
                        <span class="text-xl font-black text-indigo-600">{{ number_format($avgRating, 1) }} / 5.0</span>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">KEPUASAN PESERTA</p>
                    </div>
                </div>

                <!-- Star Distribution Chart -->
                <div class="bg-slate-50/70 rounded-2xl p-6 border border-slate-100 mb-8 space-y-2.5">
                    <p class="text-xs font-bold text-slate-700 mb-3">Distribusi Penilaian Bintang</p>
                    @foreach([5, 4, 3, 2, 1] as $star)
                        @php
                            $dist = $distribution[$star] ?? ['count' => 0, 'percentage' => 0];
                        @endphp
                        <div class="flex items-center gap-3 text-xs">
                            <span class="w-6 font-bold text-slate-600 text-right">{{ $star }}★</span>
                            <div class="flex-1 bg-slate-200 h-2.5 rounded-full overflow-hidden">
                                <div class="bg-amber-400 h-full rounded-full transition-all duration-500" style="width: {{ $dist['percentage'] }}%"></div>
                            </div>
                            <span class="w-10 text-right font-semibold text-slate-400">{{ $dist['percentage'] }}%</span>
                        </div>
                    @endforeach
                </div>

                <!-- Review Items List -->
                <div class="space-y-4">
                    @forelse($reviews as $rev)
                        <div class="p-6 bg-slate-50/50 rounded-2xl border border-slate-100 hover:border-indigo-100 transition space-y-3">
                            <div class="flex justify-between items-start">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-600 text-white rounded-xl flex items-center justify-center font-bold text-sm shadow-xs">
                                        {{ strtoupper(substr($rev->user_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-sm">{{ $rev->user_name }}</h4>
                                        <p class="text-[11px] text-slate-400 font-medium">
                                            Acara: <strong class="text-indigo-600 font-semibold">{{ $rev->event->title ?? 'Event' }}</strong>
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-amber-400 text-xs">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $rev->rating) ★ @else ☆ @endif
                                        @endfor
                                    </div>
                                    <span class="text-[10px] text-slate-400 mt-0.5 block font-medium">{{ $rev->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="p-4 bg-white rounded-xl border border-slate-100 text-sm text-slate-700 font-medium italic">
                                "{{ $rev->comment }}"
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-400 text-sm">
                            Belum ada ulasan untuk penyelenggara ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: Acara Diselenggarakan -->
        <div class="space-y-8">
            <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm space-y-6">
                <h3 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                    <span>🗓️</span> Acara Diselenggarakan
                </h3>

                <!-- Acara Aktif & Mendatang -->
                <div class="space-y-3">
                    <p class="text-[11px] font-bold text-indigo-600 uppercase tracking-wider flex items-center gap-1">
                        <span>🚀</span> ACARA AKTIF & MENDATANG ({{ $upcomingEvents->count() }})
                    </p>
                    @forelse($upcomingEvents as $evt)
                        <div class="p-5 bg-white border border-slate-200/80 rounded-2xl hover:border-indigo-500 transition group shadow-xs">
                            <span class="px-2.5 py-0.5 bg-indigo-50 text-indigo-600 rounded-md text-[9px] font-bold uppercase tracking-wider">
                                {{ $evt->category->name ?? 'Umum' }}
                            </span>
                            <h4 class="font-bold text-slate-900 text-base mt-2 group-hover:text-indigo-600 transition">{{ $evt->title }}</h4>
                            <p class="text-xs text-slate-400 mt-1 flex items-center gap-1 font-medium">
                                📅 {{ is_object($evt->date) ? $evt->date->format('d M Y, H:i') : date('d M Y, H:i', strtotime($evt->date)) }} WIB
                            </p>
                            <div class="flex justify-between items-center mt-4 pt-3 border-t border-slate-100">
                                <span class="font-extrabold text-slate-900 text-sm">Rp {{ number_format($evt->price, 0, ',', '.') }}</span>
                                <a href="{{ url('checkout?event_id=' . $evt->id) }}" class="text-xs font-bold text-indigo-600 group-hover:translate-x-1 transition flex items-center gap-0.5">
                                    Pesan Tiket →
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic">Tidak ada acara mendatang.</p>
                    @endforelse
                </div>

                <!-- Acara Selesai -->
                <div class="space-y-3 pt-4 border-t border-slate-100">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1">
                        <span>🏁</span> ACARA SELESAI ({{ $pastEvents->count() }})
                    </p>
                    @forelse($pastEvents as $evt)
                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl">
                            <span class="px-2 py-0.5 bg-slate-200 text-slate-600 rounded text-[9px] font-bold uppercase">
                                SELESAI
                            </span>
                            <h4 class="font-bold text-slate-800 text-sm mt-1.5">{{ $evt->title }}</h4>
                            <p class="text-[11px] text-slate-400 mt-0.5">
                                📅 {{ is_object($evt->date) ? $evt->date->format('d M Y') : date('d M Y', strtotime($evt->date)) }}
                            </p>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic">Belum ada acara selesai.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</main>
@endsection
