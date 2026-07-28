@extends('layouts.admin')
@section('title', 'QR Check-in Scanner')
@section('page_title', 'Check-in Scanner')
@section('page_subtitle', 'Scan QR Code tiket pengunjung untuk validasi.')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col items-center">
        <h3 class="text-xl font-black mb-6">Arahkan QR Code ke Kamera</h3>
        <div id="reader" class="w-full max-w-sm rounded-2xl overflow-hidden border-4 border-indigo-100"></div>
    </div>
    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col justify-center items-center text-center" id="result-box">
        <div class="w-20 h-20 bg-slate-100 text-slate-400 rounded-3xl flex items-center justify-center mb-6" id="status-icon">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
        </div>
        <h3 class="text-2xl font-black mb-2" id="status-title">Menunggu Scan...</h3>
        <p class="text-slate-500 font-medium" id="status-desc">Hasil validasi akan muncul di sini.</p>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const html5QrCode = new Html5Qrcode("reader");
        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            if(html5QrCode.isScanning) {
                html5QrCode.pause();
                
                // Ekstrak order_id jika QR berisi URL
                let orderId = decodedText;
                try {
                    let url = new URL(decodedText);
                    if(url.searchParams.has('order_id')) {
                        orderId = url.searchParams.get('order_id');
                    }
                } catch(e) {}

                document.getElementById('status-icon').className = "w-20 h-20 bg-indigo-100 text-indigo-600 rounded-3xl flex items-center justify-center mb-6 animate-pulse";
                document.getElementById('status-title').innerText = "Memvalidasi...";
                document.getElementById('status-title').className = "text-2xl font-black mb-2 text-indigo-600";
                
                fetch("{{ route('admin.scanner.check') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order_id: orderId })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.status === 'success') {
                        document.getElementById('status-icon').className = "w-20 h-20 bg-green-100 text-green-600 rounded-3xl flex items-center justify-center mb-6";
                        document.getElementById('status-icon').innerHTML = '<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                        document.getElementById('status-title').innerText = "Valid!";
                        document.getElementById('status-title').className = "text-2xl font-black mb-2 text-green-600";
                        document.getElementById('status-desc').innerText = data.message + "\n\nE-Certificate sedang diproses.";
                    } else {
                        document.getElementById('status-icon').className = "w-20 h-20 bg-rose-100 text-rose-600 rounded-3xl flex items-center justify-center mb-6";
                        document.getElementById('status-icon').innerHTML = '<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
                        document.getElementById('status-title').innerText = "Ditolak!";
                        document.getElementById('status-title').className = "text-2xl font-black mb-2 text-rose-600";
                        document.getElementById('status-desc').innerText = data.message;
                    }

                    setTimeout(() => {
                        html5QrCode.resume();
                        document.getElementById('status-title').innerText = "Menunggu Scan...";
                        document.getElementById('status-title').className = "text-2xl font-black mb-2";
                        document.getElementById('status-desc').innerText = "Hasil validasi akan muncul di sini.";
                        document.getElementById('status-icon').className = "w-20 h-20 bg-slate-100 text-slate-400 rounded-3xl flex items-center justify-center mb-6";
                        document.getElementById('status-icon').innerHTML = '<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>';
                    }, 4000);
                });
            }
        };
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };
        html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback);
    });
</script>
@endpush
