<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket Resmi - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
        }
    </style>
</head>

<body class="bg-indigo-600 text-white min-h-screen flex items-center justify-center p-4 md:p-8">

    <div class="max-w-md w-full my-6">
        <!-- Header Banner Success -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-white shadow-inner">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-black tracking-tight">Pembayaran Berhasil!</h1>
            <p class="text-indigo-100 text-sm mt-1 font-medium">Tiket Anda telah terbit dan siap digunakan.</p>
        </div>

        <!-- Ticket Card -->
        <div class="bg-white text-slate-900 rounded-[2.5rem] overflow-hidden shadow-2xl relative">
            <!-- Ticket Header -->
            <div class="p-8 pb-6 bg-slate-50/50 text-center relative border-b-2 border-dashed border-slate-200">
                <p class="text-indigo-600 font-bold uppercase tracking-widest text-[11px] mb-1">E-TICKET RESMI</p>
                <h2 class="text-2xl font-black text-slate-900 leading-tight">
                    {{ is_object($transaction->event) ? $transaction->event->title : 'Jazz Night 2024: A Celebration' }}
                </h2>

                <!-- Ticket Cutouts -->
                <div class="absolute -left-4 -bottom-4 w-8 h-8 bg-indigo-600 rounded-full"></div>
                <div class="absolute -right-4 -bottom-4 w-8 h-8 bg-indigo-600 rounded-full"></div>
            </div>

            <!-- Ticket Details Grid -->
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">NAMA PEMBELI</p>
                        <p class="font-bold text-slate-900 text-base truncate">{{ $transaction->customer_name ?? 'Donni Prabowo' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">TANGGAL & WAKTU</p>
                        <p class="font-bold text-slate-900 text-base">
                            @if(isset($transaction->event) && isset($transaction->event->date))
                                {{ is_object($transaction->event->date) ? $transaction->event->date->format('d M, H:i') : date('d M, H:i', strtotime($transaction->event->date)) }}
                            @else
                                16 Nov, 19:30
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">ORDER ID</p>
                        <p class="font-bold text-slate-900 text-base">{{ $transaction->order_id ?? 'TRX-99210' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">LOKASI</p>
                        <p class="font-bold text-slate-900 text-base truncate">
                            {{ is_object($transaction->event) ? ($transaction->event->location ?? 'Blue Note Lounge') : 'Blue Note Lounge' }}
                        </p>
                    </div>
                </div>

                <!-- QR Code Box -->
                <div class="bg-slate-50/80 border border-slate-100 p-6 rounded-3xl text-center">
                    <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider mb-4">SCAN QR UNTUK CHECK-IN</p>
                    <div class="inline-block p-3 bg-white border-4 border-slate-900 rounded-2xl shadow-sm">
                        <img src="{{ $qrImageUrl }}" alt="QR Code" class="w-44 h-44 object-contain mx-auto">
                    </div>
                    <p class="mt-4 font-mono font-extrabold text-slate-900 text-sm tracking-wide">{{ $ticketCode ?? 'TKT-001293848' }}</p>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="px-8 pb-8 space-y-3 no-print">
                <button onclick="window.print()"
                        class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-sm shadow-xl shadow-indigo-200 transition">
                    Cetak / Simpan PDF
                </button>

                @if(isset($transaction->order_id))
                <button onclick="sendTicketEmail('{{ $transaction->order_id }}')" id="btn-email"
                        class="w-full py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl font-bold text-xs transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span>Kirim Tiket ke Email Pembeli</span>
                </button>
                @endif

                <a href="{{ url('/') }}" class="block text-center pt-2 text-slate-500 hover:text-indigo-600 text-xs font-bold transition">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <script>
        async function sendTicketEmail(orderId) {
            const btn = document.getElementById('btn-email');
            btn.disabled = true;
            btn.innerHTML = '<span>Mengirim email...</span>';

            try {
                const res = await fetch("{{ url('/ticket/send-email') }}?order_id=" + orderId);
                const data = await res.json();
                if (data.status === 'success') {
                    alert('E-Ticket berhasil dikirimkan ke email pembeli!');
                } else {
                    alert('Gagal mengirim email: ' + (data.message || 'Error'));
                }
            } catch (err) {
                alert('Terjadi kesalahan jaringan.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg><span>Kirim Tiket ke Email Pembeli</span>';
            }
        }
    </script>
</body>

</html>