<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>E-Certificate</title>
    <style>
        body { font-family: sans-serif; text-align: center; margin: 0; padding: 0; }
        .certificate { border: 10px solid #4f46e5; padding: 50px; margin: 20px; border-radius: 20px; }
        .title { font-size: 50px; font-weight: bold; color: #4f46e5; margin-bottom: 20px; text-transform: uppercase; }
        .subtitle { font-size: 24px; color: #64748b; margin-bottom: 40px; }
        .name { font-size: 40px; font-weight: bold; color: #0f172a; margin-bottom: 40px; text-decoration: underline; }
        .event { font-size: 30px; font-weight: bold; color: #334155; margin-bottom: 20px; }
        .date { font-size: 20px; color: #64748b; margin-bottom: 50px; }
        .signature { margin-top: 50px; }
        .signature-line { width: 200px; border-bottom: 2px solid #000; margin: 0 auto 10px auto; }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="title">Certificate of Attendance</div>
        <div class="subtitle">This is to certify that</div>
        <div class="name">{{ $transaction->customer_name }}</div>
        <div class="subtitle">has successfully attended the event</div>
        <div class="event">{{ $transaction->event->title ?? 'AmikomEventHub Event' }}</div>
        <div class="date">Held on {{ $transaction->event->date ? \Carbon\Carbon::parse($transaction->event->date)->format('F d, Y') : date('F d, Y') }}</div>
        
        <div class="signature">
            <div class="signature-line"></div>
            <div>Event Organizer</div>
        </div>
    </div>
</body>
</html>
