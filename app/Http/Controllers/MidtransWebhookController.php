<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Mail\TicketMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Services\FonnteService;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        if (!$orderId) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        // Mencari ID transaksi tersebut di database lokal kita
        $transaction = Transaction::with('event')->where('order_id', $orderId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Cegah proses berulang jika status sudah lunas/sukses
        if ($transaction->status === 'settlement' || $transaction->status === 'success') {
            return response()->json(['message' => 'Already processed']);
        }

        // Logika Penerjemahan Status Midtrans API
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $transaction->status = 'challenge';
            } else if ($fraudStatus == 'accept') {
                $transaction->status = 'success';
                $this->processSuccess($transaction);
            }
        } else if ($transactionStatus == 'settlement') {
            $transaction->status = 'settlement';
            $this->processSuccess($transaction);
        } else if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $transaction->status = 'failed';
            // Restore reserved stock
            if ($transaction->event) {
                $transaction->event->increment('stock');
            }
        } else if ($transactionStatus == 'pending') {
            $transaction->status = 'pending';
        }

        $transaction->save();
        return response()->json(['message' => 'OK']);
    }

    private function processSuccess(Transaction $transaction)
    {
        $ticketCode = strtoupper('TKT-' . substr(md5($transaction->order_id), 0, 9));
        $qrData     = url('/ticket?order_id=' . $transaction->order_id);
        $qrImageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . urlencode($qrData);

        // Kirim E-Ticket ke email pembeli
        try {
            Mail::to($transaction->customer_email)->send(new TicketMail($transaction, $ticketCode, $qrImageUrl));
            Log::info("E-Ticket email sent to {$transaction->customer_email} for order {$transaction->order_id}");
        } catch (\Exception $e) {
            Log::error("Failed to send ticket email for order {$transaction->order_id}: " . $e->getMessage());
        }

        // Kirim E-Ticket ke WhatsApp
        $waMessage = "Halo {$transaction->customer_name},\n\nPembayaran Anda untuk event {$transaction->event->title} telah BERHASIL.\n\nKode Tiket: *$ticketCode*\n\nAnda dapat melihat detail E-Ticket dan QR Code Anda di link berikut:\n$qrData\n\nTerima kasih!";
        FonnteService::sendMessage($transaction->customer_phone, $waMessage);
    }
}
