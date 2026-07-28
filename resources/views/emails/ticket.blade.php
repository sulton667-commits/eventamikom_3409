<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket - AmikomEventHub</title>
    <style>
        body { font-family: Arial, sans-serif; background: #4338ca; margin: 0; padding: 20px; }
        .container { max-width: 500px; margin: 0 auto; }
        .header { text-align: center; color: white; padding: 20px 0; }
        .header h1 { font-size: 22px; margin: 8px 0; }
        .header p { color: #c7d2fe; font-size: 14px; }
        .check-circle { width: 60px; height: 60px; border: 3px solid white; border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px; }
        .ticket { background: white; border-radius: 24px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.3); }
        .ticket-header { background: #eef2ff; padding: 24px; text-align: center; border-bottom: 3px dashed #c7d2fe; position: relative; }
        .ticket-header .label { color: #6366f1; font-size: 11px; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; }
        .ticket-header h2 { font-size: 20px; margin: 6px 0 0; color: #1e293b; font-weight: 900; }
        .ticket-body { padding: 24px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; }
        .info-label { color: #94a3b8; font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
        .info-value { color: #1e293b; font-weight: 700; font-size: 16px; }
        .info-value.small { font-size: 13px; }
        .qr-box { background: #f8fafc; border-radius: 16px; padding: 20px; text-align: center; }
        .qr-label { color: #94a3b8; font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; }
        .qr-img { width: 160px; height: 160px; }
        .ticket-code { font-family: monospace; font-weight: bold; color: #1e293b; margin-top: 10px; font-size: 14px; }
        .footer { padding: 20px 24px; }
        .btn { display: block; background: #4338ca; color: white; text-align: center;
            padding: 14px; border-radius: 12px; font-weight: bold; text-decoration: none; font-size: 14px; }
        .back-link { display: block; text-align: center; margin-top: 12px; color: #64748b; font-size: 13px; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="check-circle">
                <svg width="28" height="28" fill="none" stroke="white" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1>E-Ticket Resmi Anda</h1>
            <p>Simpan dan tunjukkan QR code ini saat masuk</p>
        </div>

        <div class="ticket">
            <div class="ticket-header">
                <div class="label">E-Ticket Resmi</div>
                <h2>{{ $transaction->event->title ?? 'Event' }}</h2>
            </div>

            <div class="ticket-body">
                <div class="grid">
                    <div>
                        <div class="info-label">Nama Pembeli</div>
                        <div class="info-value">{{ $transaction->customer_name }}</div>
                    </div>
                    <div>
                        <div class="info-label">Tanggal & Waktu</div>
                        <div class="info-value small">
                            @if($transaction->event && $transaction->event->date)
                                {{ \Carbon\Carbon::parse($transaction->event->date)->format('d M, H:i') }}
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="info-label">Order ID</div>
                        <div class="info-value small">{{ $transaction->order_id }}</div>
                    </div>
                    <div>
                        <div class="info-label">Lokasi</div>
                        <div class="info-value small">{{ $transaction->event->location ?? '-' }}</div>
                    </div>
                </div>

                <div class="qr-box">
                    <div class="qr-label">Scan QR untuk Check-in</div>
                    <img class="qr-img" src="{{ $qrImageUrl }}" alt="QR Code Tiket">
                    <div class="ticket-code">{{ $ticketCode }}</div>
                </div>
            </div>

            <div class="footer">
                <a href="{{ url('/ticket?order_id=' . $transaction->order_id) }}" class="btn">Lihat E-Ticket Online</a>
                <a href="{{ url('/') }}" class="back-link">Kembali ke Beranda</a>
            </div>
        </div>

        <p style="text-align:center;color:#c7d2fe;font-size:12px;margin-top:20px;">
            © 2024 AmikomEventHub. E-ticket ini digenerate otomatis oleh sistem.<br>
            Hubungi support@eventtiket.com jika ada masalah.
        </p>
    </div>
</body>
</html>
