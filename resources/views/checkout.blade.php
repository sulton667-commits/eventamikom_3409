@extends('layouts.app')

@section('content')
<!-- Midtrans Snap JS -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<div class="min-h-screen bg-slate-50/50 py-12 px-4 sm:px-6">
    <!-- Step 1: Input Data Pemesan -->
    <div id="step-checkout" class="max-w-2xl mx-auto space-y-8">
        <div class="text-left">
            <h1 class="text-3xl font-extrabold text-slate-900">Checkout</h1>
            <p class="text-slate-500 mt-1 font-medium text-sm">Lengkapi data Anda untuk mendapatkan tiket.</p>
        </div>

        <!-- Card Pesanan Anda -->
        <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900 mb-6 pb-4 border-b border-slate-100">Pesanan Anda</h2>
            <div class="flex gap-5 items-center">
                <img src="{{ (isset($event->poster_path) && $event->poster_path && str_starts_with($event->poster_path, 'http')) ? $event->poster_path : 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=500&auto=format&fit=crop&q=60' }}" 
                     alt="Event" class="w-20 h-20 rounded-2xl object-cover">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-lg">{{ $event->title ?? 'ndx' }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5 font-medium">
                        {{ isset($event->date) ? \Carbon\Carbon::parse($event->date)->format('d M Y') : '26 Jul 2026' }} • {{ $event->location ?? 'kridosono' }}
                    </p>
                    <p class="text-indigo-600 font-extrabold text-sm mt-1.5">1 x Rp {{ number_format($event->price ?? 100000, 0, ',', '.') }}</p>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-slate-100 space-y-3">
                <div class="flex justify-between text-slate-500 text-sm font-medium">
                    <span>Harga Tiket</span>
                    <span class="text-slate-800">Rp {{ number_format($event->price ?? 100000, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-slate-500 text-sm font-medium">
                    <span>Biaya Layanan</span>
                    <span class="text-slate-800">Rp 5.000</span>
                </div>
                <div class="flex justify-between items-center text-xl font-extrabold text-slate-900 pt-4 border-t border-slate-100">
                    <span>Total Bayar</span>
                    <span class="text-indigo-600 font-black text-2xl">Rp {{ number_format(($event->price ?? 100000) + 5000, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Card Data Pemesan -->
        <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm">
            <h2 class="text-base font-bold text-indigo-600 italic mb-6">📦 Data Pemesan (Tanpa Login)</h2>
            <form id="form-pemesan" onsubmit="handleLanjutPembayaran(event)" class="space-y-5">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id ?? 1 }}">

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <input type="text" name="customer_name" id="input-nama" required placeholder="Nama Lengkap Anda" value="{{ Auth::check() ? Auth::user()->name : '' }}"
                           class="w-full px-5 py-3.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-500 focus:bg-white transition">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email Aktif</label>
                        <input type="email" name="customer_email" id="input-email" required placeholder="email@gmail.com" value="{{ Auth::check() ? Auth::user()->email : '' }}"
                               class="w-full px-5 py-3.5 bg-indigo-50/40 border border-indigo-100 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-500 transition">
                        <p class="text-[10px] text-slate-400 mt-1 font-semibold uppercase tracking-tight">*E-TICKET AKAN DIKIRIM KE EMAIL INI</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">No. WhatsApp</label>
                        <input type="tel" name="customer_phone" id="input-whatsapp" required placeholder="08xxxxxxxxxx" value="08123456789"
                               class="w-full px-5 py-3.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-500 focus:bg-white transition">
                    </div>
                </div>

                <button type="submit" id="btn-submit"
                        class="w-full py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-2xl font-bold text-base shadow-lg shadow-indigo-200 transition transform hover:-translate-y-0.5 active:translate-y-0 mt-4 flex items-center justify-center gap-2">
                    <span id="btn-text">Lanjut Pembayaran</span>
                    <span id="btn-spinner" class="hidden">
                        <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
                <p class="text-center text-xs text-slate-400 mt-3 font-medium">Dengan menekan tombol di atas, Anda menyetujui Syarat & Ketentuan kami.</p>
            </form>
        </div>
    </div>

    <!-- Step 2: Selesaikan Pembayaran -->
    <div id="step-payment" class="max-w-xl mx-auto hidden">
        <div class="bg-white rounded-3xl border border-slate-100 p-10 text-center shadow-sm">
            <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-6 text-indigo-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>

            <h2 class="text-2xl font-extrabold text-slate-900 mb-2">Selesaikan Pembayaran</h2>
            <p class="text-slate-500 text-sm font-medium">Mohon selesaikan pembayaran tiket Anda<br>untuk event <strong class="text-slate-800">{{ $event->title ?? 'ndx' }}</strong>.</p>

            <div class="my-8 p-6 bg-slate-50/70 rounded-2xl border border-slate-100">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">TOTAL TAGIHAN</p>
                <p id="payment-total-price" class="text-3xl font-black text-indigo-600 my-1">Rp {{ number_format(($event->price ?? 100000) + 5000, 0, ',', '.') }}</p>
                <p id="payment-order-id" class="text-xs text-slate-400 font-medium">Order ID: -</p>
            </div>

            <button type="button" onclick="payWithMidtrans()"
                    class="w-full py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-2xl font-bold text-base shadow-xl shadow-indigo-200 transition transform hover:-translate-y-0.5">
                Bayar Sekarang
            </button>
        </div>
    </div>
</div>

<script>
    let currentSnapToken = null;
    let currentOrderId = null;

    async function handleLanjutPembayaran(e) {
        e.preventDefault();

        const btnText = document.getElementById('btn-text');
        const btnSpinner = document.getElementById('btn-spinner');
        const btnSubmit = document.getElementById('btn-submit');

        btnText.textContent = "Memproses...";
        btnSpinner.classList.remove('hidden');
        btnSubmit.disabled = true;

        const form = document.getElementById('form-pemesan');
        const formData = new FormData(form);

        try {
            const response = await fetch("{{ route('checkout.process') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: formData
            });

            const data = await response.json();

            if (data.status === 'success') {
                currentSnapToken = data.snap_token;
                currentOrderId = data.order_id;

                if (!currentSnapToken) {
                    alert("Pemesanan berhasil! Tiket Anda (GRATIS) telah diterbitkan.");
                    window.location.href = "{{ url('/ticket') }}?order_id=" + currentOrderId;
                    return;
                }

                document.getElementById('payment-order-id').textContent = 'Order ID: ' + data.order_id;
                if (data.total_price) {
                    document.getElementById('payment-total-price').textContent = 'Rp ' + data.total_price;
                }

                document.getElementById('step-checkout').classList.add('hidden');
                document.getElementById('step-payment').classList.remove('hidden');
                window.scrollTo({ top: 0, behavior: 'smooth' });

                // Langsung tampilkan pop-up Midtrans Snap
                payWithMidtrans();
            } else {
                alert("Gagal memproses checkout: " + (data.message || 'Terjadi kesalahan'));
            }
        } catch (err) {
            console.error(err);
            alert("Terjadi kesalahan jaringan.");
        } finally {
            btnText.textContent = "Lanjut Pembayaran";
            btnSpinner.classList.add('hidden');
            btnSubmit.disabled = false;
        }
    }

    function payWithMidtrans() {
        if (!currentSnapToken) {
            alert("Snap Token belum dibuat. Silakan coba lagi.");
            return;
        }

        if (window.snap) {
            window.snap.pay(currentSnapToken, {
                onSuccess: function(result) {
                    alert("Pembayaran Berhasil!");
                    window.location.href = "{{ url('/ticket') }}?order_id=" + currentOrderId;
                },
                onPending: function(result) {
                    alert("Menunggu pembayaran Anda.");
                    window.location.href = "{{ url('/ticket') }}?order_id=" + currentOrderId;
                },
                onError: function(result) {
                    alert("Pembayaran gagal!");
                },
                onClose: function() {
                    console.log('User menolak atau menutup pop-up Midtrans.');
                }
            });
        } else {
            alert("SDK Midtrans belum dimuat dengan benar.");
        }
    }
</script>
@endsection