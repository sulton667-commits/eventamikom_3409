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
        } else {
            $events = Event::latest()->get();
        }

        $eventIds = $events->pluck('id')->toArray();

        // Get transactions for these events
        if ($partner) {
            $transactions = Transaction::whereIn('event_id', $eventIds)->with('event')->latest()->get();
        } else {
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

    public function scanTicket(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'partner') {
            return redirect()->route('partner.login');
        }

        $user = Auth::user();
        $partner = Partner::where('user_id', $user->id)->first();
        if (!$partner) {
            $partner = Partner::where('name', 'like', "%{$user->name}%")->first();
        }

        $events = $partner ? Event::where('partner_id', $partner->id)->latest()->get() : Event::latest()->get();
        $selectedEventId = $request->query('event_id', 'all');
        $stats = $this->calculateStats($partner, $selectedEventId);

        return view('partner.scan-ticket', compact(
            'user',
            'partner',
            'events',
            'selectedEventId',
            'stats'
        ));
    }

    public function getStats(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'partner') {
            return response()->json(['status' => 'error', 'message' => 'Akses ditolak.'], 403);
        }

        $user = Auth::user();
        $partner = Partner::where('user_id', $user->id)->first() ?? Partner::where('name', 'like', "%{$user->name}%")->first();

        $eventId = $request->query('event_id', 'all');
        $stats = $this->calculateStats($partner, $eventId);

        return response()->json([
            'status' => 'success',
            'total_checkin' => $stats['total_checkin'],
            'total_paid' => $stats['total_paid']
        ]);
    }

    private function calculateStats($partner, $eventId = 'all')
    {
        if ($partner) {
            $events = Event::where('partner_id', $partner->id)->get();
        } else {
            $events = Event::all();
        }

        $eventIds = $events->pluck('id')->toArray();

        $query = Transaction::query();
        if ($partner) {
            $query->whereIn('event_id', $eventIds);
        }

        if ($eventId && $eventId !== 'all') {
            $query->where('event_id', $eventId);
        }

        $transactions = $query->get();

        $paidTransactions = $transactions->filter(function ($t) {
            $st = strtolower($t->status);
            return in_array($st, ['settlement', 'success', 'lunas', 'paid', 'lunas / paid']);
        });

        $totalPaid = $paidTransactions->count();
        $totalCheckin = $transactions->filter(function ($t) {
            return (bool)$t->is_used;
        })->count();

        return [
            'total_checkin' => $totalCheckin,
            'total_paid' => $totalPaid,
        ];
    }

    public function checkIn(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'partner') {
            return response()->json(['status' => 'error', 'message' => 'Akses ditolak.'], 403);
        }

        $user = Auth::user();
        $partner = Partner::where('user_id', $user->id)->first() ?? Partner::where('name', 'like', "%{$user->name}%")->first();

        $input = trim($request->order_id);
        $selectedEventId = $request->event_id ?? 'all';

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
            $stats = $this->calculateStats($partner, $selectedEventId);
            return response()->json([
                'status' => 'error',
                'message' => 'Tiket dengan Kode / Order ID "' . $input . '" tidak ditemukan.',
                'total_checkin' => $stats['total_checkin'],
                'total_paid' => $stats['total_paid']
            ]);
        }

        // Filter event check if specific event is selected
        if ($selectedEventId && $selectedEventId !== 'all' && $transaction->event_id != $selectedEventId) {
            $stats = $this->calculateStats($partner, $selectedEventId);
            return response()->json([
                'status' => 'error',
                'message' => 'Tiket ini terdaftar untuk acara lain (' . ($transaction->event->title ?? 'Acara Berbeda') . '), bukan acara yang dipilih!',
                'total_checkin' => $stats['total_checkin'],
                'total_paid' => $stats['total_paid']
            ]);
        }

        // Check if ticket is already used (double entry check)
        if ($transaction->is_used) {
            $stats = $this->calculateStats($partner, $selectedEventId);
            $scannedAt = $transaction->updated_at ? $transaction->updated_at->format('H:i:s, d M Y') : 'sebelumnya';
            return response()->json([
                'status' => 'error',
                'message' => 'Tiket ini sudah pernah digunakan untuk Check-in pada ' . $scannedAt . '! (Double Entry)',
                'customer_name' => $transaction->customer_name,
                'event_title' => $transaction->event->title ?? 'Event',
                'total_checkin' => $stats['total_checkin'],
                'total_paid' => $stats['total_paid']
            ]);
        }

        // Auto-settle pending status if ticket is presented for checkin
        $statusLower = strtolower($transaction->status);
        if (in_array($statusLower, ['pending', 'unpaid'])) {
            $transaction->update(['status' => 'success']);
        }

        // Mark as used
        $transaction->update(['is_used' => true]);

        // Generate E-Certificate in background/try-catch
        $this->generateAndSendCertificate($transaction);

        $stats = $this->calculateStats($partner, $selectedEventId);

        return response()->json([
            'status' => 'success',
            'message' => 'Check-in Berhasil untuk ' . $transaction->customer_name . ' (' . ($transaction->event->title ?? 'Event') . ')',
            'customer_name' => $transaction->customer_name,
            'event_title' => $transaction->event->title ?? 'Event',
            'total_checkin' => $stats['total_checkin'],
            'total_paid' => $stats['total_paid']
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
