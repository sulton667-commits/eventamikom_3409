<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Partner;
use App\Models\Transaction;
use App\Services\FonnteService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PartnerDashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check() || Auth::user()->role !== 'partner') {
            return redirect()->route('partner.login');
        }

        $user = Auth::user();
        
        // Find partner associated with logged in user
        $partner = Partner::where('user_id', $user->id)->first();
        if (!$partner) {
            $partner = Partner::where('name', 'like', "%{$user->name}%")->first();
        }

        // Get events for this partner
        if ($partner) {
            $events = Event::where('partner_id', $partner->id)->latest()->get();
            if ($events->isEmpty()) {
                $events = Event::latest()->get();
            }
        } else {
            $events = Event::latest()->get();
        }

        $eventIds = $events->pluck('id')->toArray();

        // Get transactions for these events
        $transactions = Transaction::whereIn('event_id', $eventIds)->with('event')->latest()->get();
        if ($transactions->isEmpty()) {
            $transactions = Transaction::with('event')->latest()->get();
        }

        // Summary Calculations
        $eventsCount = $events->count();

        $paidTransactions = $transactions->filter(function ($t) {
            $st = strtolower($t->status);
            return in_array($st, ['settlement', 'success', 'lunas', 'paid', 'lunas / paid']);
        });

        $ticketsSold = $paidTransactions->count();
        $totalRevenue = $paidTransactions->sum('total_price');

        return view('partner.dashboard', compact(
            'user',
            'partner',
            'events',
            'transactions',
            'eventsCount',
            'ticketsSold',
            'totalRevenue'
        ));
    }

    public function checkIn(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'partner') {
            return response()->json(['status' => 'error', 'message' => 'Akses ditolak.'], 403);
        }

        $orderId = trim($request->order_id);

        if (!$orderId) {
            return response()->json(['status' => 'error', 'message' => 'Order ID tidak boleh kosong.']);
        }

        $transaction = Transaction::with('event')->where('order_id', $orderId)->first();

        if (!$transaction) {
            return response()->json(['status' => 'error', 'message' => 'Tiket tidak ditemukan.']);
        }

        $statusLower = strtolower($transaction->status);
        if (!in_array($statusLower, ['success', 'settlement', 'lunas', 'paid', 'lunas / paid'])) {
            return response()->json(['status' => 'error', 'message' => 'Tiket belum lunas (Status: ' . strtoupper($transaction->status) . ').']);
        }

        if ($transaction->is_used) {
            return response()->json(['status' => 'error', 'message' => 'Tiket ini sudah digunakan! (Double Entry)']);
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
            if (class_exists(Pdf::class) && view()->exists('admin.certificate_pdf')) {
                $pdf = Pdf::loadView('admin.certificate_pdf', compact('transaction'));
                $pdfContent = $pdf->output();

                $fileName = 'Certificate_' . $transaction->order_id . '.pdf';

                Mail::raw("Halo {$transaction->customer_name},\n\nTerima kasih telah hadir di acara " . ($transaction->event->title ?? 'Event') . ".\nBerikut terlampir E-Certificate Anda.", function ($message) use ($transaction, $pdfContent, $fileName) {
                    $message->to($transaction->customer_email)
                            ->subject('E-Certificate Kehadiran - ' . ($transaction->event->title ?? 'Event'))
                            ->attachData($pdfContent, $fileName, [
                                'mime' => 'application/pdf',
                            ]);
                });

                $waMessage = "Halo {$transaction->customer_name},\n\nTerima kasih telah check-in di " . ($transaction->event->title ?? 'Event') . "!\n\nE-Certificate Anda telah dikirim ke email: {$transaction->customer_email}";
                FonnteService::sendMessage($transaction->customer_phone, $waMessage);
            }
        } catch (\Exception $e) {
            Log::error("Failed to generate/send certificate: " . $e->getMessage());
        }
    }
}
