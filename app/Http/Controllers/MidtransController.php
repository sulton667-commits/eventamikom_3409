<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        // Tangkap payload notifikasi dari Midtrans
        $payload = $request->all();
        Log::info('Midtrans Callback Payload:', $payload);

        $orderId           = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $type              = $payload['payment_type'] ?? null;

        // Logika penanganan status transaksi Midtrans
        if ($transactionStatus == 'capture') {
            if ($type == 'credit_card') {
                // Berhasil
            }
        } elseif ($transactionStatus == 'settlement') {
            // Pembayaran Berhasil
        } elseif ($transactionStatus == 'pending') {
            // Menunggu Pembayaran
        } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            // Gagal / Batal
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Notification processed successfully',
        ]);
    }
}
