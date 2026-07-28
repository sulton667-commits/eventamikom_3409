<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Mail\TicketMail;
use Illuminate\Support\Facades\Mail;

class TicketController extends Controller
{
    public function show(Request $request)
    {
        $orderId = $request->get('order_id');

        if ($orderId) {
            $transaction = Transaction::with('event')->where('order_id', $orderId)->first();
        } else {
            // Ambil transaksi paling akhir jika tidak ada order_id
            $transaction = Transaction::with('event')->latest()->first();
        }

        if ($transaction && isset($transaction->status) && $transaction->status === 'pending') {
            // Ubah status ke success dan kurangi stok tiket
            $transaction->status = 'success';
            $transaction->save();

            if ($transaction->event && $transaction->event->stock > 0) {
                $transaction->event->decrement('stock', 1);
            }
        }

        if (!$transaction) {
            // Dummy fallback untuk tampilan demo
            $transaction = (object)[
                'order_id'       => 'TRX-99210',
                'customer_name'  => 'Donni Prabowo',
                'customer_email' => 'donni@gmail.com',
                'total_price'    => 105000,
                'status'         => 'success',
                'event'          => (object)[
                    'title'    => 'Jazz Night 2024: A Celebration',
                    'date'     => '2026-11-16 19:30:00',
                    'location' => 'Blue Note Lounge',
                ]
            ];
        }

        // Buat ticket code unik
        $ticketCode = strtoupper('TKT-' . substr(md5($transaction->order_id ?? 'TRX-99210'), 0, 9));

        // QR Code URL via API
        $qrData     = url('/ticket?order_id=' . ($transaction->order_id ?? 'TRX-99210'));
        $qrImageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . urlencode($qrData);

        return view('ticket', compact('transaction', 'ticketCode', 'qrImageUrl'));
    }

    public function sendEmail(Request $request)
    {
        $orderId = $request->get('order_id');
        $transaction = Transaction::with('event')->where('order_id', $orderId)->first();

        if (!$transaction) {
            return response()->json(['status' => 'error', 'message' => 'Transaction not found'], 404);
        }

        $ticketCode = strtoupper('TKT-' . substr(md5($transaction->order_id), 0, 9));
        $qrData     = url('/ticket?order_id=' . $transaction->order_id);
        $qrImageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . urlencode($qrData);

        try {
            Mail::to($transaction->customer_email)->send(new TicketMail($transaction, $ticketCode, $qrImageUrl));
            return response()->json(['status' => 'success', 'message' => 'Email tiket berhasil dikirim!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
