<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Partner - {{ $partner->name ?? ($user->name ?? 'HMSSI Amikom') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f3f6f5;
        }

        .bg-header {
            background-color: #04584c;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col justify-between text-slate-800">

    <!-- Top Partner Navigation Header -->
    <header class="bg-header text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex flex-wrap justify-between items-center gap-4">
            
            <!-- Left Branding & User Details -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center p-1 shadow-sm shrink-0">
                    @if(isset($partner) && $partner->logo_path)
                        <img src="{{ $partner->logo_path }}" alt="Logo" class="w-full h-full object-contain rounded-full">
                    @else
                        <div class="w-full h-full bg-emerald-600 rounded-full flex items-center justify-center text-white font-extrabold text-xs">
                            AH
                        </div>
                    @endif
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-extrabold text-base tracking-tight text-white">
                            {{ $partner->name ?? ($user->name ?? 'HMSSI Amikom') }}
                        </span>
                        <span class="bg-[#086b5c] text-emerald-200 text-[10px] font-extrabold uppercase px-2.5 py-0.5 rounded-full tracking-wider border border-emerald-400/30">
                            PARTNER ACTIVE
                        </span>
                    </div>
                    <p class="text-teal-100/90 text-xs mt-0.5 font-medium">
                        Logged in as: <strong class="text-white font-semibold">{{ $user->name ?? 'HMSSI Amikom' }}</strong> 
                        <span class="text-teal-200/80">({{ $user->email ?? 'hmssi@amikom.ac.id' }})</span>
                    </p>
                </div>
            </div>

            <!-- Right Action Buttons -->
            <div class="flex items-center gap-2.5">
                <!-- Scanner Button -->
                <a href="{{ route('partner.scan-ticket') }}" class="bg-white hover:bg-slate-100 text-slate-800 font-bold px-4 py-2 rounded-full text-xs flex items-center gap-2 shadow-sm transition active:scale-95">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                    </svg>
                    <span>Scanner Hari-H</span>
                </a>

                <!-- Beranda Link -->
                <a href="{{ url('/') }}" class="text-teal-100 hover:text-white font-semibold text-xs px-3 py-2 transition flex items-center gap-1">
                    <span>&larr; Beranda</span>
                </a>

                <!-- Logout Button -->
                <form method="POST" action="{{ route('user.logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white font-bold text-xs px-4 py-2 rounded-full shadow-sm transition active:scale-95">
                        Logout
                    </button>
                </form>
            </div>

        </div>
    </header>

    <!-- Main Dashboard Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-grow w-full">

        <!-- Top Summary Cards (3 Grid Items) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            
            <!-- Card 1: Acara Diselenggarakan -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">
                        ACARA DISELENGGARAKAN
                    </h3>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-slate-900">{{ $eventsCount }}</span>
                        <span class="text-slate-600 font-semibold text-sm">Event</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Total Tiket Terjual -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between relative">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            TOTAL TIKET TERJUAL
                        </h3>
                        <a href="#daftar-pembeli" class="bg-emerald-100 text-emerald-700 hover:bg-emerald-200 text-[10px] font-extrabold px-2.5 py-0.5 rounded uppercase tracking-wider transition">
                            LIHAT PEMBELI
                        </a>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-slate-900">{{ $ticketsSold }}</span>
                        <span class="text-slate-600 font-semibold text-sm">Tiket</span>
                    </div>
                </div>
                <a href="#daftar-pembeli" class="text-emerald-600 hover:text-emerald-700 font-bold text-xs mt-3 inline-flex items-center gap-1 transition">
                    <span>Klik untuk melihat rincian siapa saja pembeli tiket</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <!-- Card 3: Total Pendapatan -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">
                        TOTAL PENDAPATAN
                    </h3>
                    <div class="text-3xl font-extrabold text-emerald-600">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </div>
                </div>
            </div>

        </div>

        <!-- Section 1: Daftar Acara Kepanitiaan -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 mb-8">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-7 h-7 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h2 class="text-base font-extrabold text-slate-900">Daftar Acara Kepanitiaan</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="py-3 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">NAMA ACARA</th>
                            <th class="py-3 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">TANGGAL</th>
                            <th class="py-3 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">HARGA TIKET</th>
                            <th class="py-3 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">LOKASI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($events as $event)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-4 px-4 font-bold text-slate-900 text-sm">
                                    {{ $event->title }}
                                </td>
                                <td class="py-4 px-4 text-slate-500 text-sm font-medium">
                                    {{ \Carbon\Carbon::parse($event->date)->translatedFormat('d M Y, H:i') }}
                                </td>
                                <td class="py-4 px-4 font-bold text-emerald-600 text-sm">
                                    Rp {{ number_format($event->price, 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-4 text-slate-600 text-sm">
                                    {{ $event->location }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400 text-sm">
                                    Belum ada acara kepanitiaan yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section 2: Daftar Pembeli Tiket -->
        <div id="daftar-pembeli" class="bg-white rounded-2xl border border-emerald-300/80 shadow-sm p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-extrabold text-slate-900">Daftar Pembeli Tiket</h2>
                        <span class="bg-emerald-600 text-white font-bold text-xs px-2.5 py-0.5 rounded-full">
                            {{ count($transactions) }} Pesanan
                        </span>
                    </div>
                </div>
            </div>
            <p class="text-slate-500 text-xs mb-5">
                Daftar pelanggan yang telah membeli tiket untuk acara organisasi Anda.
            </p>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="py-3 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">NAMA PEMBELI</th>
                            <th class="py-3 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">KONTAK PELANGGAN</th>
                            <th class="py-3 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">NAMA ACARA</th>
                            <th class="py-3 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">ORDER ID</th>
                            <th class="py-3 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">NOMINAL</th>
                            <th class="py-3 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">TANGGAL TRANSAKSI</th>
                            <th class="py-3 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">STATUS PEMBAYARAN</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transactions as $tx)
                            @php
                                $initial = strtoupper(substr($tx->customer_name ?? 'U', 0, 1));
                                $statusLower = strtolower($tx->status ?? 'pending');
                                $isPaid = in_array($statusLower, ['settlement', 'success', 'lunas', 'paid', 'lunas / paid']);
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 font-extrabold text-xs flex items-center justify-center shrink-0">
                                            {{ $initial }}
                                        </div>
                                        <span class="font-bold text-slate-900 text-xs">
                                            {{ $tx->customer_name }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-bold text-slate-800 text-xs">
                                        {{ $tx->customer_email }}
                                    </div>
                                    <div class="text-slate-400 text-[11px]">
                                        {{ $tx->customer_phone }}
                                    </div>
                                </td>
                                <td class="py-4 px-4 font-bold text-slate-800 text-xs">
                                    {{ $tx->event->title ?? '-' }}
                                </td>
                                <td class="py-4 px-4 font-mono text-xs text-slate-700 font-semibold">
                                    {{ $tx->order_id }}
                                </td>
                                <td class="py-4 px-4 font-bold text-emerald-600 text-xs">
                                    Rp {{ number_format($tx->total_price, 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-4 text-slate-500 text-xs">
                                    {{ \Carbon\Carbon::parse($tx->created_at)->translatedFormat('d M Y, H:i') }}
                                </td>
                                <td class="py-4 px-4">
                                    @if($isPaid)
                                        <span class="bg-emerald-100 text-emerald-700 font-extrabold text-[10px] px-3 py-1 rounded-full uppercase tracking-wider inline-block">
                                            LUNAS / PAID
                                        </span>
                                    @else
                                        <span class="bg-amber-100 text-amber-700 font-extrabold text-[10px] px-3 py-1 rounded-full uppercase tracking-wider inline-block">
                                            PENDING
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400 text-sm">
                                    Belum ada transaksi pembeli tiket.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 px-6 text-center text-xs text-slate-500">
        <div class="flex items-center justify-center gap-1">
            <span class="w-4 h-4 bg-emerald-600 rounded text-white text-[9px] font-bold inline-flex items-center justify-center">AH</span>
            <span>&copy; {{ date('Y') }} AmikomEventHub &mdash; Panel Partner & Kepanitiaan.</span>
        </div>
    </footer>

    <!-- Interactive QR Check-in Scanner Modal -->
    <div id="scannerModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl shadow-2xl max-w-xl w-full p-6 relative border border-slate-100 animate-fadeIn">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900">Scanner Tiket Hari-H</h3>
                        <p class="text-slate-500 text-xs">Scan QR code tiket pengunjung atau masukkan Order ID.</p>
                    </div>
                </div>
                <button onclick="closeScannerModal()" class="w-8 h-8 text-slate-400 hover:text-slate-600 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center font-bold text-sm transition">
                    ✕
                </button>
            </div>

            <!-- Modal Content (Camera Feed & Scanner Result) -->
            <div class="space-y-4">
                <div class="bg-slate-900 rounded-2xl overflow-hidden p-2 text-center relative border-4 border-emerald-100">
                    <div id="partner-reader" class="w-full max-w-sm mx-auto rounded-xl"></div>
                </div>

                <!-- Manual Input Fallback -->
                <form id="manualCheckinForm" onsubmit="handleManualSubmit(event)" class="flex gap-2">
                    <input type="text" id="manualOrderId" placeholder="Masukkan Order ID (cth: TRX-1785266855-gbxtK)" 
                        class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition">
                        Verifikasi
                    </button>
                </form>

                <!-- Status Feedback Card -->
                <div id="modal-status-box" class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 text-center">
                    <div id="modal-status-icon" class="w-12 h-12 bg-slate-200 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    </div>
                    <h4 id="modal-status-title" class="text-base font-extrabold text-slate-800">Menunggu Scan...</h4>
                    <p id="modal-status-desc" class="text-slate-500 text-xs mt-1">Arahkan kamera ke QR tiket pelanggan untuk Check-in otomatis.</p>
                </div>
            </div>

        </div>
    </div>

    <!-- Scanner Script -->
    <script>
        let html5QrCode = null;

        function openScannerModal() {
            document.getElementById('scannerModal').classList.remove('hidden');
            startScanner();
        }

        function closeScannerModal() {
            document.getElementById('scannerModal').classList.add('hidden');
            if (html5QrCode) {
                html5QrCode.stop().catch(() => {});
            }
        }

        function startScanner() {
            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode("partner-reader");
            }
            const config = { fps: 10, qrbox: { width: 220, height: 220 } };
            html5QrCode.start(
                { facingMode: "environment" },
                config,
                (decodedText) => {
                    if(html5QrCode.isScanning) {
                        html5QrCode.pause();
                        let orderId = decodedText;
                        try {
                            let url = new URL(decodedText);
                            if(url.searchParams.has('order_id')) {
                                orderId = url.searchParams.get('order_id');
                            }
                        } catch(e) {}
                        processCheckIn(orderId);
                    }
                }
            ).catch(err => {
                console.warn("Kamera tidak tersedia:", err);
            });
        }

        function handleManualSubmit(e) {
            e.preventDefault();
            const orderId = document.getElementById('manualOrderId').value.trim();
            if (orderId) {
                processCheckIn(orderId);
            }
        }

        function processCheckIn(orderId) {
            const icon = document.getElementById('modal-status-icon');
            const title = document.getElementById('modal-status-title');
            const desc = document.getElementById('modal-status-desc');

            icon.className = "w-12 h-12 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-2 animate-pulse";
            title.innerText = "Memvalidasi Tiket...";
            title.className = "text-base font-extrabold text-indigo-600";
            desc.innerText = "Mohon tunggu sejenak.";

            fetch("{{ route('partner.scanner.check') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ order_id: orderId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    icon.className = "w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-2";
                    icon.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                    title.innerText = "Valid! Check-in Berhasil";
                    title.className = "text-base font-extrabold text-emerald-600";
                    desc.innerText = data.message;
                } else {
                    icon.className = "w-12 h-12 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-2";
                    icon.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
                    title.innerText = "Ditolak / Gagal Check-in";
                    title.className = "text-base font-extrabold text-rose-600";
                    desc.innerText = data.message;
                }

                setTimeout(() => {
                    if(html5QrCode && html5QrCode.getState() === 3) {
                        html5QrCode.resume();
                    }
                    title.innerText = "Menunggu Scan...";
                    title.className = "text-base font-extrabold text-slate-800";
                    desc.innerText = "Arahkan kamera ke QR tiket pelanggan untuk Check-in otomatis.";
                    icon.className = "w-12 h-12 bg-slate-200 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-2";
                    icon.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>';
                }, 4000);
            })
            .catch(err => {
                icon.className = "w-12 h-12 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-2";
                title.innerText = "Terjadi Kesalahan Network";
                title.className = "text-base font-extrabold text-rose-600";
                desc.innerText = "Gagal terhubung ke server.";
            });
        }
    </script>

</body>

</html>
