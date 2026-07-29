@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-6 py-16 md:py-24 flex flex-col md:flex-row items-center gap-12">
        <div class="flex-1 space-y-8">
            <span class="inline-block px-4 py-1.5 bg-indigo-100/80 text-indigo-600 rounded-full text-xs font-bold uppercase tracking-wider">
                #1 EVENT PLATFORM
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold leading-tight text-slate-900 tracking-tight">
                Temukan & Pesan <span class="text-indigo-600">Tiket Event</span> Impianmu.
            </h1>
            <p class="text-base md:text-lg text-slate-500 max-w-lg leading-relaxed">
                Dari konser musik hingga workshop teknologi, semua ada di genggamanmu. Pesan aman & cepat dengan Midtrans.
            </p>
            <div class="flex flex-wrap gap-4 pt-2">
                <a href="#events" class="px-7 py-3.5 bg-indigo-600 text-white rounded-xl font-bold text-sm shadow-xl shadow-indigo-200 hover:bg-indigo-700 hover:scale-105 transition-all">
                    Mulai Jelajah
                </a>
                <a href="{{ url('/bantuan') }}" class="px-7 py-3.5 bg-slate-100 text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-200 transition">
                    Cara Pesan
                </a>
            </div>
        </div>

        <div class="flex-1 relative w-full">
            <div class="relative mx-auto max-w-md md:max-w-none">
                <!-- Background decorative glow -->
                <div class="absolute -top-10 -left-10 w-72 h-72 bg-indigo-400/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-10 -right-10 w-72 h-72 bg-purple-400/20 rounded-full blur-3xl"></div>

                <!-- Main Hero Poster Card -->
                <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-slate-100 bg-white">
                    <img src="/images/concert.png" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format%26fit=crop%26w=1000%26q=80';" alt="Event Poster" class="w-full h-[420px] object-cover">
                </div>

                <!-- Floating Verification Badge -->
                <div class="absolute -bottom-6 -left-4 glass p-4 rounded-2xl shadow-xl z-20 border border-white flex items-center gap-3.5 bg-white/90 backdrop-blur-md">
                    <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">TERVERIFIKASI</p>
                        <p class="text-xs font-bold text-slate-800">Pembayaran Aman via Midtrans</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Jelajahi Kategori Section -->
    <section id="kategori" class="max-w-7xl mx-auto px-6 py-12">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-900 mb-1">Jelajahi Kategori</h2>
            <p class="text-sm text-slate-500 font-medium">Temukan event sesuai minatmu</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button onclick="filterCategory('all')" class="category-btn active px-6 py-2.5 rounded-full text-xs font-bold transition bg-indigo-600 text-white shadow-md shadow-indigo-200">
                Semua
            </button>
            @if(isset($categories) && count($categories) > 0)
                @foreach($categories as $category)
                    <button onclick="filterCategory('{{ strtolower($category->name) }}')" class="category-btn px-6 py-2.5 rounded-full text-xs font-semibold bg-white text-slate-600 border border-slate-200 hover:border-indigo-600 hover:text-indigo-600 transition">
                        {{ $category->name }}
                    </button>
                @endforeach
            @else
                <button onclick="filterCategory('entertainment')" class="category-btn px-6 py-2.5 rounded-full text-xs font-semibold bg-white text-slate-600 border border-slate-200 hover:border-indigo-600 hover:text-indigo-600 transition">
                    Entertainment
                </button>
                <button onclick="filterCategory('futsal')" class="category-btn px-6 py-2.5 rounded-full text-xs font-semibold bg-white text-slate-600 border border-slate-200 hover:border-indigo-600 hover:text-indigo-600 transition">
                    Futsal
                </button>
                <button onclick="filterCategory('musik')" class="category-btn px-6 py-2.5 rounded-full text-xs font-semibold bg-white text-slate-600 border border-slate-200 hover:border-indigo-600 hover:text-indigo-600 transition">
                    Musik
                </button>
                <button onclick="filterCategory('seminar it')" class="category-btn px-6 py-2.5 rounded-full text-xs font-semibold bg-white text-slate-600 border border-slate-200 hover:border-indigo-600 hover:text-indigo-600 transition">
                    Seminar IT
                </button>
            @endif
        </div>
    </section>

    <!-- Event Terdekat Section -->
    <section id="events" class="max-w-7xl mx-auto px-6 py-12">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-1">Event Terdekat</h2>
            <p class="text-sm text-slate-500 font-medium">Jangan sampai ketinggalan acara seru minggu ini!</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($events as $event)
                <div class="event-card group bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col justify-between" data-category="{{ strtolower($event->category->name ?? 'umum') }}">
                    <div>
                        <div class="relative overflow-hidden aspect-[4/3]">
                            @php
                                $posterSrc = ($event->poster_path && str_starts_with($event->poster_path, 'http'))
                                    ? $event->poster_path
                                    : '/images/concert.png';
                            @endphp
                            <img src="{{ $posterSrc }}" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format%26fit=crop%26w=800%26q=80';" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute top-3 left-3 px-3 py-1 bg-white/90 backdrop-blur rounded-full text-[10px] font-bold uppercase tracking-wider text-indigo-600 shadow-sm">
                                {{ $event->category->name ?? 'Umum' }}
                            </div>
                        </div>

                        <div class="p-6">
                            <h3 class="text-lg font-bold text-slate-900 mb-3 group-hover:text-indigo-600 transition line-clamp-1">
                                {{ $event->title }}
                            </h3>
                            
                            <div class="space-y-2 text-xs text-slate-500 mb-6">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>
                                        @if(is_object($event->date))
                                            {{ $event->date->format('d M Y') }}
                                        @else
                                            {{ date('d M Y', strtotime($event->date)) }}
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $event->location ?? 'Amikom Yogyakarta' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 pt-0 border-t border-slate-100 flex items-center justify-between mt-auto pt-4">
                        <div>
                            @if($event->price == 0)
                                <span class="text-lg font-extrabold text-indigo-600">Gratis</span>
                            @else
                                <span class="text-lg font-extrabold text-indigo-600">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        <a href="{{ url('event-detail/' . $event->id) }}" class="px-4 py-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-xl text-xs font-bold transition">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16 bg-white rounded-2xl border border-slate-100">
                    <p class="text-slate-400 text-sm font-medium">Belum ada event yang ditemukan.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Partner Kami Section -->
    <section class="max-w-7xl mx-auto px-6 py-16 border-t border-slate-200/60 mt-12">
        <div class="text-center max-w-xl mx-auto mb-10">
            <h2 class="text-2xl font-bold text-slate-900 mb-2">Partner Kami</h2>
            <p class="text-sm text-slate-500">Didukung oleh berbagai penyelenggara dan perusahaan terpercaya</p>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-8">
            @forelse($partners as $partner)
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col items-center gap-2 w-44 hover:shadow-md transition">
                    @if($partner->logo_path && str_starts_with($partner->logo_path, 'http'))
                        <img src="{{ $partner->logo_path }}" alt="{{ $partner->name }}" class="w-12 h-12 object-cover rounded-xl shadow-sm border border-slate-100">
                    @else
                        <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 font-bold text-lg">
                            {{ strtoupper(substr($partner->name, 0, 2)) }}
                        </div>
                    @endif
                    <span class="text-xs font-bold text-slate-700 text-center">{{ $partner->name }}</span>
                </div>
            @empty
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col items-center gap-2 w-44">
                    <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 font-bold text-lg">
                        AH
                    </div>
                    <span class="text-xs font-bold text-slate-700">Amikom</span>
                </div>
            @endforelse
        </div>
    </section>

    <script>
        function filterCategory(catName) {
            const buttons = document.querySelectorAll('.category-btn');
            buttons.forEach(btn => {
                btn.classList.remove('bg-indigo-600', 'text-white', 'shadow-md', 'shadow-indigo-200');
                btn.classList.add('bg-white', 'text-slate-600', 'border', 'border-slate-200');
            });

            event.target.classList.remove('bg-white', 'text-slate-600', 'border', 'border-slate-200');
            event.target.classList.add('bg-indigo-600', 'text-white', 'shadow-md', 'shadow-indigo-200');

            const cards = document.querySelectorAll('.event-card');
            cards.forEach(card => {
                if (catName === 'all' || card.dataset.category.includes(catName)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
@endsection