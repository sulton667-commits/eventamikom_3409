<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gatekeeper Scanner - Anti-Fraud Gate Protection</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #07090e;
            color: #f1f5f9;
        }

        .bg-dark-card {
            background-color: #0d111d;
        }

        .pulse-emerald {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse-emerald 2s infinite;
        }

        @keyframes pulse-emerald {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
            }
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }
    </style>
</head>

<body class="min-h-screen flex flex-col justify-between selection:bg-emerald-500 selection:text-white">

    <!-- Top Gatekeeper Navigation Header -->
    <header class="border-b border-slate-800 bg-[#0a0d16] px-4 sm:px-8 py-4">
        <div class="max-w-4xl mx-auto flex justify-between items-center gap-4">
            
            <!-- Left Branding -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/10 rounded-xl p-1.5 flex items-center justify-center border border-white/10 shrink-0">
                    @if(isset($partner) && $partner->logo_path)
                        <img src="{{ $partner->logo_path }}" alt="Logo" class="w-full h-full object-contain rounded-lg">
                    @else
                        <div class="w-full h-full bg-emerald-500 rounded-lg flex items-center justify-center text-white font-extrabold text-xs">
                            AE
                        </div>
                    @endif
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 pulse-emerald"></span>
                        <h1 class="font-extrabold text-lg tracking-tight text-white flex items-center gap-2">
                            Gatekeeper Scanner
                        </h1>
                    </div>
                    <p class="text-xs text-slate-400 font-medium">Anti-Fraud Gate Protection</p>
                </div>
            </div>

            <!-- Right Partner Badge -->
            <div class="text-right">
                <div class="font-bold text-sm text-slate-200">
                    {{ $partner->name ?? ($user->name ?? 'HMSSI Amikom') }}
                </div>
                <span class="inline-block bg-emerald-500/20 text-emerald-400 text-[10px] font-extrabold px-2.5 py-0.5 rounded-full tracking-wider border border-emerald-500/30 uppercase mt-0.5">
                    PANITIA
                </span>
            </div>

        </div>
    </header>

    <!-- Main Gatekeeper Container -->
    <main class="max-w-3xl mx-auto px-4 py-8 flex-grow w-full space-y-6">

        <!-- Back Button to Partner Dashboard -->
        <div>
            <a href="{{ route('partner.dashboard') }}" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-slate-300 font-bold px-5 py-2.5 rounded-xl text-xs border border-slate-800 transition active:scale-95">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali ke Dashboard Partner</span>
            </a>
        </div>

        <!-- Realtime Stat Card -->
        <div class="bg-dark-card border border-slate-800 rounded-2xl p-5 sm:p-6 shadow-xl flex flex-wrap justify-between items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 011 1.732V15a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 011-1.732V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-1">
                        TOTAL CHECK-IN REALTIME
                    </span>
                    <div class="text-3xl font-extrabold text-white tracking-tight flex items-baseline gap-2">
                        <span id="stat-checkin" class="text-emerald-400">{{ $stats['total_checkin'] }}</span>
                        <span class="text-slate-400 text-lg font-semibold">/ <span id="stat-paid">{{ $stats['total_paid'] }}</span> Tiket Lunas</span>
                    </div>
                </div>
            </div>

            <!-- Live Status Indicator -->
            <div class="flex items-center gap-2 bg-emerald-950/60 border border-emerald-500/30 px-3.5 py-1.5 rounded-full">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                <span class="text-xs font-extrabold text-emerald-400 tracking-wide uppercase">System Live</span>
            </div>
        </div>

        <!-- Event Filter Dropdown -->
        <div class="space-y-2">
            <label for="event-select" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">
                PILIH ACARA REGISTRASI:
            </label>
            <div class="relative">
                <select id="event-select" onchange="changeEventFilter(this.value)" class="w-full bg-dark-card text-white text-sm font-semibold border border-slate-800 rounded-xl px-4 py-3.5 appearance-none focus:outline-none focus:border-emerald-500 transition cursor-pointer">
                    <option value="all" {{ $selectedEventId == 'all' ? 'selected' : '' }}>
                        -- Semua Event (Universal Check-In) --
                    </option>
                    @foreach($events as $ev)
                        <option value="{{ $ev->id }}" {{ $selectedEventId == $ev->id ? 'selected' : '' }}>
                            {{ $ev->title }}
                        </option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Scanner Card Container -->
        <div class="bg-dark-card border border-slate-800 rounded-3xl p-6 sm:p-8 space-y-6 shadow-2xl relative overflow-hidden">

            <!-- Camera Header Status -->
            <div class="flex justify-between items-center border-b border-slate-800/80 pb-4">
                <div class="flex items-center gap-2">
                    <span id="camera-status-dot" class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                    <span id="camera-status-text" class="text-xs font-bold text-emerald-400 tracking-wide">
                        ● Kamera Siap / Active
                    </span>
                </div>
                <button onclick="toggleManualInput()" class="text-xs text-slate-400 hover:text-emerald-400 font-semibold underline transition">
                    Ketik Kode Manual
                </button>
            </div>

            <!-- HTML5 QR Code Reader Viewport -->
            <div class="relative min-h-[280px] bg-[#05070d] rounded-2xl border-2 border-dashed border-slate-800 flex flex-col items-center justify-center p-4 overflow-hidden" id="reader-container">
                
                <div id="reader" class="w-full max-w-sm rounded-xl overflow-hidden"></div>

                <!-- Camera Action Buttons overlay/fallback -->
                <div id="camera-fallback" class="text-center py-6 space-y-4">
                    <div class="w-16 h-16 bg-slate-900 border border-slate-800 rounded-2xl flex items-center justify-center mx-auto text-emerald-400 shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h0.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>

                    <div class="space-y-3 max-w-xs mx-auto">
                        <button onclick="startCameraScanner()" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold py-3 px-6 rounded-xl text-sm transition shadow-lg shadow-indigo-600/30 active:scale-95">
                            Request Camera Permissions
                        </button>
                        
                        <label class="block w-full bg-slate-900 hover:bg-slate-800 text-slate-300 font-bold py-2.5 px-4 rounded-xl text-xs border border-slate-700 cursor-pointer transition text-center">
                            <span>Scan an Image File</span>
                            <input type="file" id="qr-input-file" accept="image/*" class="hidden" onchange="scanImageFile(this)">
                        </label>
                    </div>
                </div>

            </div>

            <!-- Manual Input Form (Toggleable) -->
            <div id="manual-input-box" class="hidden bg-slate-900/90 border border-slate-800 rounded-2xl p-4 space-y-3">
                <label class="block text-xs font-bold text-slate-300">Ketik Order ID / Kode Tiket (Contoh: TRX-XXXXXXXXX / TKT-XXXXXXX):</label>
                <form onsubmit="handleManualSubmit(event)" class="flex gap-2">
                    <input type="text" id="manual-order-id" placeholder="Masukkan Order ID atau Kode..." class="flex-grow bg-dark-card text-white text-sm font-bold border border-slate-700 rounded-xl px-4 py-2.5 focus:outline-none focus:border-emerald-500">
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold px-5 py-2.5 rounded-xl text-xs transition active:scale-95">
                        Submit
                    </button>
                </form>
            </div>

            <!-- Validation Result Alert Display -->
            <div id="scan-result-card" class="bg-slate-900/60 border border-slate-800 rounded-2xl p-5 text-center transition-all">
                <div id="result-icon-container" class="w-14 h-14 bg-slate-800 text-slate-400 rounded-2xl flex items-center justify-center mx-auto mb-3 border border-slate-700">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                    </svg>
                </div>
                <h4 id="result-title" class="text-lg font-black text-white mb-1">Menunggu Scan QR...</h4>
                <p id="result-message" class="text-xs text-slate-400 font-medium max-w-md mx-auto">
                    Arahkan kamera ke QR Code tiket pengunjung untuk memverifikasi keabsahan.
                </p>
            </div>

        </div>

    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-800/80 py-4 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} Gatekeeper Scanner • AmikomEventHub Anti-Fraud Protection
    </footer>

    <!-- JavaScript Logic -->
    <script>
        let html5QrCode = null;
        let currentEventId = "{{ $selectedEventId }}";

        document.addEventListener("DOMContentLoaded", function() {
            startCameraScanner();
        });

        function changeEventFilter(eventId) {
            currentEventId = eventId;
            fetch(`{{ route('partner.scanner.stats') }}?event_id=${eventId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById('stat-checkin').innerText = data.total_checkin;
                        document.getElementById('stat-paid').innerText = data.total_paid;
                    }
                });
        }

        function startCameraScanner() {
            document.getElementById('camera-fallback').style.display = 'none';

            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode("reader");
            }

            const config = { fps: 10, qrbox: { width: 220, height: 220 } };

            html5QrCode.start(
                { facingMode: "environment" },
                config,
                (decodedText) => {
                    if (html5QrCode && html5QrCode.isScanning) {
                        html5QrCode.pause();
                        
                        let orderId = decodedText;
                        try {
                            let url = new URL(decodedText);
                            if(url.searchParams.has('order_id')) {
                                orderId = url.searchParams.get('order_id');
                            }
                        } catch(e) {}

                        verifyCheckIn(orderId);
                    }
                }
            ).catch(err => {
                console.warn("Kamera tidak tersedia:", err);
                document.getElementById('camera-fallback').style.display = 'block';
                document.getElementById('camera-status-dot').className = "w-2.5 h-2.5 rounded-full bg-rose-500";
                document.getElementById('camera-status-text').innerText = "● Kamera Nonaktif / Izin Diperlukan";
                document.getElementById('camera-status-text').className = "text-xs font-bold text-rose-400 tracking-wide";
            });
        }

        function scanImageFile(input) {
            if (input.files && input.files[0]) {
                const imageFile = input.files[0];
                if (!html5QrCode) {
                    html5QrCode = new Html5Qrcode("reader");
                }

                html5QrCode.scanFile(imageFile, true)
                    .then(decodedText => {
                        let orderId = decodedText;
                        try {
                            let url = new URL(decodedText);
                            if(url.searchParams.has('order_id')) {
                                orderId = url.searchParams.get('order_id');
                            }
                        } catch(e) {}

                        verifyCheckIn(orderId);
                    })
                    .catch(err => {
                        showScanResult('error', 'QR Code tidak terdeteksi pada gambar yang diunggah.');
                    });
            }
        }

        function toggleManualInput() {
            const box = document.getElementById('manual-input-box');
            box.classList.toggle('hidden');
        }

        function handleManualSubmit(e) {
            e.preventDefault();
            const inputVal = document.getElementById('manual-order-id').value.trim();
            if (inputVal) {
                verifyCheckIn(inputVal);
            }
        }

        function verifyCheckIn(orderId) {
            showScanResult('loading', 'Memverifikasi Tiket...', 'Mohon tunggu sejenak.');

            fetch("{{ route('partner.scanner.check') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    order_id: orderId,
                    event_id: currentEventId
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.total_checkin !== undefined) {
                    document.getElementById('stat-checkin').innerText = data.total_checkin;
                }
                if (data.total_paid !== undefined) {
                    document.getElementById('stat-paid').innerText = data.total_paid;
                }

                if (data.status === 'success') {
                    showScanResult('success', 'VALID! CHECK-IN BERHASIL', data.message);
                } else {
                    showScanResult('error', 'DITOLAK / GAGAL CHECK-IN', data.message);
                }

                setTimeout(() => {
                    if (html5QrCode && html5QrCode.getState() === 3) {
                        html5QrCode.resume();
                    }
                    showScanResult('idle', 'Menunggu Scan QR...', 'Arahkan kamera ke QR Code tiket pengunjung untuk memverifikasi keabsahan.');
                }, 5000);
            })
            .catch(err => {
                showScanResult('error', 'TERJADI KESALAHAN NETWORK', 'Gagal terhubung ke server untuk verifikasi.');
            });
        }

        function showScanResult(type, title, message) {
            const card = document.getElementById('scan-result-card');
            const icon = document.getElementById('result-icon-container');
            const titleEl = document.getElementById('result-title');
            const msgEl = document.getElementById('result-message');

            titleEl.innerText = title;
            msgEl.innerText = message || '';

            if (type === 'loading') {
                card.className = "bg-indigo-950/40 border border-indigo-500/40 rounded-2xl p-5 text-center transition-all animate-pulse";
                icon.className = "w-14 h-14 bg-indigo-500/20 text-indigo-400 rounded-2xl flex items-center justify-center mx-auto mb-3 border border-indigo-500/40";
                icon.innerHTML = '<svg class="w-7 h-7 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>';
                titleEl.className = "text-lg font-black text-indigo-400 mb-1";
            } else if (type === 'success') {
                card.className = "bg-emerald-950/60 border border-emerald-500/50 rounded-2xl p-5 text-center transition-all shadow-lg shadow-emerald-950/50";
                icon.className = "w-14 h-14 bg-emerald-500/20 text-emerald-400 rounded-2xl flex items-center justify-center mx-auto mb-3 border border-emerald-500/40";
                icon.innerHTML = '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>';
                titleEl.className = "text-lg font-black text-emerald-400 mb-1";
            } else if (type === 'error') {
                card.className = "bg-rose-950/60 border border-rose-500/50 rounded-2xl p-5 text-center transition-all shadow-lg shadow-rose-950/50";
                icon.className = "w-14 h-14 bg-rose-500/20 text-rose-400 rounded-2xl flex items-center justify-center mx-auto mb-3 border border-rose-500/40";
                icon.innerHTML = '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>';
                titleEl.className = "text-lg font-black text-rose-400 mb-1";
            } else {
                card.className = "bg-slate-900/60 border border-slate-800 rounded-2xl p-5 text-center transition-all";
                icon.className = "w-14 h-14 bg-slate-800 text-slate-400 rounded-2xl flex items-center justify-center mx-auto mb-3 border border-slate-700";
                icon.innerHTML = '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>';
                titleEl.className = "text-lg font-black text-white mb-1";
            }
        }
    </script>
</body>

</html>
