<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Services\FonnteService;

class ScannerController extends Controller
{
    public function index()
    {
        return view('admin.scanner');
    }

    public function checkIn(Request $request)
    {
        $input = trim($request->order_id);

        if (!$input) {
            return response()->json(['status' => 'error', 'message' => 'Order ID / QR Code tidak boleh kosong.']);
        }

        // Extract Order ID if input is a URL or query string containing order_id
        $orderId = $input;
        if (filter_var($input, FILTER_VALIDATE_URL)) {
            $parsedUrl = parse_url($input);
            if (isset($parsedUrl['query'])) {
                parse_str($parsedUrl['query'], $queryParams);
                if (!empty($queryParams['order_id'])) {
                    $orderId = $queryParams['order_id'];
                }
            }
        }

        // Extract TRX-... pattern from string if present
        if (preg_match('/(TRX-[A-Za-z0-9-]+)/i', $orderId, $matches)) {
            $orderId = $matches[1];
        }

        // Find transaction by order_id
        $transaction = Transaction::with('event')->where('order_id', $orderId)->first();

        // Fallback: Case-insensitive search
        if (!$transaction) {
            $transaction = Transaction::with('event')->where('order_id', 'like', $orderId)->first();
        }

        // Fallback: Search by Ticket Code (TKT-XXXXXXX)
        if (!$transaction) {
            $allTransactions = Transaction::with('event')->get();
            foreach ($allTransactions as $t) {
                $code = strtoupper('TKT-' . substr(md5($t->order_id), 0, 9));
                if (strtoupper($input) === $code) {
                    $transaction = $t;
                    break;
                }
            }
        }

        if (!$transaction) {
            return response()->json(['status' => 'error', 'message' => 'Tiket dengan Kode / Order ID "' . $input . '" tidak ditemukan.']);
        }

        // Check if ticket is already used (double entry check)
        if ($transaction->is_used) {
            return response()->json(['status' => 'error', 'message' => 'Tiket ini sudah pernah digunakan sebelumnya! (Double Entry)']);
        }

        // Auto-settle pending status if ticket is presented for checkin
        $statusLower = strtolower($transaction->status);
        if (in_array($statusLower, ['pending', 'unpaid'])) {
            $transaction->update(['status' => 'success']);
        }

        // Mark as used
        $transaction->update(['is_used' => true]);

        // Generate E-Certificate
        $this->generateAndSendCertificate($transaction);

        return response()->json([
            'status' => 'success',
            'message' => 'Check-in Berhasil untuk ' . $transaction->customer_name . ' (' . ($transaction->event->title ?? 'Event') . ')'
        ]);
    }

    private function generateAndSendCertificate(Transaction $transaction)
    {
        try {
            $pdf = Pdf::loadView('admin.certificate_pdf', compact('transaction'));
            $pdfContent = $pdf->output();

            $fileName = 'Certificate_' . $transaction->order_id . '.pdf';
            
            // Send via Email
            Mail::raw("Halo {$transaction->customer_name},\n\nTerima kasih telah hadir di acara {$transaction->event->title}.\nBerikut terlampir E-Certificate Anda.", function ($message) use ($transaction, $pdfContent, $fileName) {
                $message->to($transaction->customer_email)
                        ->subject('E-Certificate Kehadiran - ' . $transaction->event->title)
                        ->attachData($pdfContent, $fileName, [
                            'mime' => 'application/pdf',
                        ]);
            });

            // Send WA Notification
            $waMessage = "Halo {$transaction->customer_name},\n\nTerima kasih telah check-in di {$transaction->event->title}!\n\nE-Certificate Anda telah kami kirimkan ke email: {$transaction->customer_email}\nSilakan periksa inbox/spam Anda.";
            FonnteService::sendMessage($transaction->customer_phone, $waMessage);

        } catch (\Exception $e) {
            \Log::error("Failed to generate/send certificate: " . $e->getMessage());
        }
    }
}
