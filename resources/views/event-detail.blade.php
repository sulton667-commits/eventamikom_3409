@extends('layouts.app')

@section('content')
<main class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-3 gap-12">
    <!-- Left Column: Poster & Penyelenggara Acara -->
    <div class="lg:col-span-1">
        <div class="sticky top-28 space-y-6">
            <!-- Poster Card -->
            <div class="overflow-hidden rounded-[2.5rem] shadow-2xl border-8 border-white bg-slate-900 aspect-[4/5]">
                @php
                    $posterSrc = (isset($event->poster_path) && $event->poster_path && str_starts_with($event->poster_path, 'http'))
                        ? $event->poster_path
                        : asset('concert.png');
                @endphp
                <img src="{{ $posterSrc }}" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=1000&q=80';" alt="{{ $event->title ?? 'Event' }}" class="w-full h-full object-cover">

            </div>

            <!-- Penyelenggara Acara Card (Sesuai Gambar 1) -->
            <div class="p-6 bg-white rounded-3xl border border-slate-100 shadow-sm space-y-4">
                <h4 class="font-bold text-slate-900 text-sm">Penyelenggara Acara</h4>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center font-extrabold text-sm shadow-md shrink-0">
                        {{ strtoupper(substr($organizer->name ?? 'HMSSI Amikom', 0, 2)) }}
                    </div>
                    <div>
                        <h5 class="font-extrabold text-slate-900 text-sm leading-tight">{{ $organizer->name ?? 'HMSSI Amikom' }}</h5>
                        <div class="flex items-center gap-1 mt-0.5 text-xs text-amber-500 font-bold">
                            <span>⭐ {{ number_format($avgRating ?? 4.0, 1) }}</span>
                            <span class="text-slate-400 font-normal">({{ $totalCount ?? 4 }} ulasan)</span>
                        </div>
                        <a href="{{ route('partner.profile.public', $organizer->id ?? 1) }}" class="text-[11px] font-bold text-indigo-600 hover:underline mt-1 inline-block">
                            👇 Lihat Rekam Jejak Ulasan →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Details & Ulasan -->
    <div class="lg:col-span-2 space-y-10">
        <!-- Event Header Info -->
        <div class="space-y-4">
            <span class="px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold uppercase tracking-wider">
                {{ is_object($event->category) ? $event->category->name : 'Musik' }}
            </span>
            <h1 class="text-4xl md:text-5xl font-black leading-tight text-slate-900">{{ $event->title ?? 'ndx' }}</h1>
            <div class="flex flex-wrap gap-6 text-slate-500 font-medium text-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>
                        @if(isset($event->date) && is_object($event->date))
                            {{ $event->date->format('d M Y, H:i') }}
                        @else
                            26 Jul 2026, 01:20
                        @endif
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>{{ $event->location ?? 'kridosono' }}</span>
                </div>
            </div>
        </div>

        <!-- Deskripsi Event -->
        <div class="space-y-3">
            <h3 class="text-xl font-extrabold text-slate-900">Deskripsi Event</h3>
            <p class="text-base text-slate-600 leading-relaxed font-medium">
                {{ $event->description ?? 'ayolahh bisa' }}
            </p>
        </div>

        <!-- Price & Action Banner Card -->
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-[2.5rem] p-8 md:p-10 text-white shadow-xl shadow-indigo-200 relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                <div>
                    <p class="text-indigo-200 font-bold uppercase tracking-widest text-xs mb-1">HARGA TIKET</p>
                    <h2 class="text-4xl font-black">Rp {{ number_format($event->price ?? 100000, 0, ',', '.') }} <span class="text-sm font-normal text-indigo-200">/ orang</span></h2>
                    <p class="mt-2 text-xs text-indigo-100 flex items-center gap-1.5 font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Sisa stok: <a href="#" class="font-bold underline">{{ $event->stock ?? 7 }} Tiket lagi!</a>
                    </p>
                </div>
                <div>
                    <a href="{{ url('checkout?event_id=' . ($event->id ?? 1)) }}"
                       class="inline-block px-8 py-4 bg-white text-indigo-600 hover:bg-slate-50 rounded-2xl font-black text-lg transition shadow-lg transform hover:-translate-y-0.5">
                        Pesan Sekarang
                    </a>
                </div>
            </div>
            <div class="absolute -right-16 -bottom-16 w-56 h-56 bg-white/10 rounded-full"></div>
        </div>

        <!-- Kebijakan Tiket -->
        <div class="space-y-3 pt-2">
            <h3 class="text-lg font-extrabold text-slate-900">Kebijakan Tiket</h3>
            <ul class="space-y-2.5 text-xs text-slate-500 font-medium">
                <li class="flex items-center gap-2">
                    <span class="text-emerald-500 font-bold">✔</span>
                    E-Ticket akan dikirimkan otomatis setelah pembayaran berhasil.
                </li>
                <li class="flex items-center gap-2">
                    <span class="text-emerald-500 font-bold">✔</span>
                    Tiket dapat discan di pintu masuk (Check-in).
                </li>
                <li class="flex items-center gap-2 text-rose-500">
                    <span class="font-bold">🚫</span>
                    Tiket yang sudah dibeli tidak dapat direfund.
                </li>
            </ul>
        </div>

        <!-- Ulasan & Penilaian Section (Sesuai Gambar 1) -->
        <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm space-y-6">
            <div>
                <h3 class="text-2xl font-extrabold text-slate-900">Ulasan & Penilaian</h3>
                <p class="text-xs text-slate-500 font-medium mt-1">
                    Rata-rata Rating: <span class="text-amber-500 font-bold">⭐ {{ number_format($avgRating ?? 5.0, 1) }}</span> / 5.0 ({{ $totalCount ?? 1 }} Ulasan)
                </p>
            </div>

            <!-- Box Input Ulasan atau Notice Login -->
            @auth
                <form action="{{ route('review.store') }}" method="POST" class="p-6 bg-slate-50/70 border border-slate-100 rounded-2xl space-y-4">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event->id ?? 1 }}">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Beri Penilaian</label>
                        <select name="rating" class="px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-amber-500 focus:outline-none">
                            <option value="5">⭐⭐⭐⭐⭐ (5.0)</option>
                            <option value="4">⭐⭐⭐⭐☆ (4.0)</option>
                            <option value="3">⭐⭐⭐☆☆ (3.0)</option>
                            <option value="2">⭐⭐☆☆☆ (2.0)</option>
                            <option value="1">⭐☆☆☆☆ (1.0)</option>
                        </select>
                    </div>
                    <textarea name="comment" required rows="2" placeholder="Tuliskan ulasan Anda mengenai event ini..."
                              class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-500 transition"></textarea>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-md">
                        Kirim Ulasan
                    </button>
                </form>
            @else
                <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl text-center text-xs text-slate-500 font-medium italic">
                    Silakan <a href="{{ route('user.login') }}" class="text-indigo-600 font-bold underline not-italic">login</a> terlebih dahulu untuk memberikan ulasan.
                </div>
            @endauth

            <!-- List Ulasan Customer -->
            <div class="space-y-4">
                @forelse($reviews as $rev)
                    <div class="p-5 bg-slate-50/50 border border-slate-100 rounded-2xl space-y-2">
                        <div class="flex justify-between items-center">
                            <h4 class="font-bold text-slate-900 text-sm">{{ $rev->user_name }}</h4>
                            <span class="text-[10px] text-slate-400 font-medium">{{ $rev->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="text-amber-400 text-xs">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $rev->rating) ★ @else ☆ @endif
                            @endfor
                        </div>
                        <p class="text-xs text-slate-700 font-medium leading-relaxed">
                            {{ $rev->comment }}
                        </p>
                    </div>
                @empty
                    <div class="p-5 bg-slate-50/50 border border-slate-100 rounded-2xl space-y-2">
                        <div class="flex justify-between items-center">
                            <h4 class="font-bold text-slate-900 text-sm">Abdul Muadz</h4>
                            <span class="text-[10px] text-slate-400 font-medium">3 days ago</span>
                        </div>
                        <div class="text-amber-400 text-xs">⭐⭐⭐⭐⭐</div>
                        <p class="text-xs text-slate-700 font-medium">gacor wakk</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</main>
@endsection