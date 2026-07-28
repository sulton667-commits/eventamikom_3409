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
        $orderId = $request->order_id;
        
        $transaction = Transaction::with('event')->where('order_id', $orderId)->first();

        if (!$transaction) {
            return response()->json(['status' => 'error', 'message' => 'Tiket tidak ditemukan.']);
        }

        if (!in_array($transaction->status, ['success', 'settlement'])) {
            return response()->json(['status' => 'error', 'message' => 'Tiket belum lunas (Status: ' . $transaction->status . ').']);
        }

        if ($transaction->is_used) {
            return response()->json(['status' => 'error', 'message' => 'Tiket ini sudah digunakan! (Double Entry)']);
        }

        // Tandai sebagai digunakan
        $transaction->update(['is_used' => true]);

        // Generate E-Certificate in background (async)
        // For demonstration, we do it sync, but in real app we dispatch a job
        $this->generateAndSendCertificate($transaction);

        return response()->json([
            'status' => 'success', 
            'message' => 'Check-in Berhasil untuk ' . $transaction->customer_name
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
