@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-12">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Katalog Event</h1>
        <p class="text-slate-500 font-medium">Temukan seluruh event yang tersedia di AmikomEventHub</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($events ?? \App\Models\Event::with('category')->latest()->get() as $event)
            <div class="group bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="relative overflow-hidden aspect-[4/3]">
                        @php
                            $posterSrc = ($event->poster_path && str_starts_with($event->poster_path, 'http'))
                                ? $event->poster_path
                                : asset('assets/concert.png');
                        @endphp
                        <img src="{{ $posterSrc }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                        <div class="absolute top-3 left-3 px-3 py-1 bg-white/90 backdrop-blur rounded-full text-[10px] font-bold uppercase text-indigo-600 shadow-sm">
                            {{ $event->category->name ?? 'Umum' }}
                        </div>
                    </div>

                    <div class="p-6">
                        <h3 class="text-lg font-bold text-slate-900 mb-3 group-hover:text-indigo-600 transition">
                            {{ $event->title }}
                        </h3>
                        <div class="space-y-2 text-xs text-slate-500 mb-4">
                            <div class="flex items-center gap-2">
                                <span>📅 {{ is_object($event->date) ? $event->date->format('d M Y') : date('d M Y', strtotime($event->date)) }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span>📍 {{ $event->location ?? 'Amikom Yogyakarta' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 pt-0 border-t border-slate-100 flex items-center justify-between mt-auto pt-4">
                    <span class="text-lg font-extrabold text-indigo-600">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                    <a href="{{ url('event-detail/' . $event->id) }}" class="px-4 py-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-xl text-xs font-bold transition">
                        Lihat Detail
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-16 bg-white rounded-2xl border border-slate-100">
                <p class="text-slate-400 text-sm">Belum ada event dalam katalog.</p>
            </div>
        @endforelse
    </div>
</section>
@endsection