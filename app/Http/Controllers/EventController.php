<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Partner;
use App\Models\Transaction;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function show($id = null)
    {
        $event = $id ? Event::with(['category', 'partner', 'reviews'])->find($id) : Event::with(['category', 'partner', 'reviews'])->first();
        if (!$event) {
            $event = (object)[
                'id'          => 1,
                'title'       => 'ndx',
                'description' => 'ayolahh bisa',
                'date'        => \Carbon\Carbon::parse('2026-07-26 01:20:00'),
                'location'    => 'kridosono',
                'price'       => 100000,
                'stock'       => 7,
                'poster_path' => null,
                'category'    => (object)['name' => 'Musik'],
                'pricing_tiers' => null
            ];
        }

        // Evaluate Pricing Tiers (Step 6)
        if (isset($event->pricing_tiers) && is_array($event->pricing_tiers)) {
            $now = now();
            foreach ($event->pricing_tiers as $tier) {
                if (isset($tier['start_date']) && isset($tier['end_date']) && isset($tier['price'])) {
                    if ($now->between($tier['start_date'], $tier['end_date'])) {
                        $event->price = $tier['price'];
                        $event->active_tier_name = $tier['name'] ?? 'Promo';
                        break;
                    }
                }
            }
        }

        $organizer  = is_object($event) && isset($event->partner) ? $event->partner : Partner::first();
        $reviews    = is_object($event) && isset($event->id) ? \App\Models\Review::where('event_id', $event->id)->latest()->get() : collect();
        if ($reviews->isEmpty() && $organizer) {
            $reviews = \App\Models\Review::where('partner_id', $organizer->id)->latest()->get();
        }

        $avgRating  = $reviews->count() > 0 ? round($reviews->avg('rating'), 1) : 5.0;
        $totalCount = $reviews->count();

        return view('event-detail', compact('event', 'organizer', 'reviews', 'avgRating', 'totalCount'));
    }

    public function checkout(Request $request)
    {
        $eventId = $request->get('event_id');
        $event   = $eventId ? Event::with('category')->find($eventId) : Event::with('category')->first();

        if (!$event) {
            abort(404, 'Event tidak ditemukan.');
        }

        if (isset($event->pricing_tiers) && is_array($event->pricing_tiers)) {
            $now = now();
            foreach ($event->pricing_tiers as $tier) {
                if (isset($tier['start_date']) && isset($tier['end_date']) && isset($tier['price'])) {
                    if ($now->between($tier['start_date'], $tier['end_date'])) {
                        $event->price = $tier['price'];
                        break;
                    }
                }
            }
        }

        return view('checkout', compact('event'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        $event = Event::find($request->event_id) ?? Event::first();
        if ($event && $event->stock <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Maaf, tiket sudah habis dipesan.'
            ], 400);
        }

        if ($event && isset($event->pricing_tiers) && is_array($event->pricing_tiers)) {
            $now = now();
            foreach ($event->pricing_tiers as $tier) {
                if (isset($tier['start_date']) && isset($tier['end_date']) && isset($tier['price'])) {
                    if ($now->between($tier['start_date'], $tier['end_date'])) {
                        $event->price = $tier['price'];
                        break;
                    }
                }
            }
        }

        $ticketPrice = $event ? $event->price : 100000;
        
        // Cek Kupon (jika ada) - Step 6
        $discount = 0;
        if ($request->has('voucher_code')) {
            $voucher = \App\Models\Voucher::where('code', $request->voucher_code)
                ->where('quota', '>', 0)
                ->where(function($q) {
                    $q->whereNull('valid_until')->orWhere('valid_until', '>=', now());
                })->first();
            if ($voucher) {
                $discount = $voucher->is_percentage ? ($ticketPrice * $voucher->discount_amount / 100) : $voucher->discount_amount;
                $voucher->decrement('quota');
            }
        }

        $serviceFee  = ($ticketPrice <= 0) ? 0 : 5000;
        $totalPrice  = max(0, $ticketPrice - $discount + $serviceFee);
        $orderId     = 'TRX-' . time() . '-' . Str::upper(Str::random(5));

        // Kurangi stok segera setelah checkout ditekan (Reserved Ticket)
        if ($event) {
            $event->decrement('stock');
        }

        if ($totalPrice <= 0) {
            // Bypass Midtrans untuk Event Gratis (Step 8)
            $transaction = Transaction::create([
                'event_id'       => $event->id ?? 1,
                'order_id'       => $orderId,
                'customer_name'  => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'total_price'    => $totalPrice,
                'status'         => 'success',
            ]);

            // Kirim WA Tiket Gratis (Step 3)
            $waMessage = "Halo {$request->customer_name},\n\nTerima kasih telah mendaftar di event {$event->title}.\nPesanan Anda ({$orderId}) GRATIS dan telah berhasil.\nSilakan cek email Anda atau halaman tiket Anda.";
            FonnteService::sendMessage($request->customer_phone, $waMessage);

            return response()->json([
                'status'     => 'success',
                'snap_token' => null,
                'order_id'   => $orderId,
                'total_price'=> 0,
            ]);
        }

        // Simpan Transaksi di Database (Berbayar)
        $transaction = Transaction::create([
            'event_id'       => $event->id ?? 1,
            'order_id'       => $orderId,
            'customer_name'  => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'total_price'    => $totalPrice,
            'status'         => 'pending',
        ]);

        // Konfigurasi Midtrans SDK
        \Midtrans\Config::$serverKey    = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production', false);
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;
        \Midtrans\Config::$curlOptions  = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER     => [],
        ];

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $request->customer_name,
                'email'      => $request->customer_email,
                'phone'      => $request->customer_phone,
            ],
            'item_details' => [
                [
                    'id'       => 'EVENT-' . ($event->id ?? 1),
                    'price'    => (int) ($ticketPrice - $discount),
                    'quantity' => 1,
                    'name'     => substr($event->title ?? 'Tiket Event', 0, 50),
                ],
                [
                    'id'       => 'FEE-001',
                    'price'    => (int) $serviceFee,
                    'quantity' => 1,
                    'name'     => 'Biaya Layanan',
                ]
            ]
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $transaction->snap_token = $snapToken;
            $transaction->save();

            // Abandoned Cart WA Notification (Step 3)
            $paymentUrl = "https://app.sandbox.midtrans.com/snap/v2/vtweb/" . $snapToken; // Asumsi sandbox
            $waMessage = "Hai {$request->customer_name},\n\nPesanan Anda ({$orderId}) untuk tiket {$event->title} berhasil dibuat!\n\nJangan lupa selesaikan pembayaran sebesar Rp " . number_format($totalPrice, 0, ',', '.') . " di link berikut agar tiket Anda tidak hangus:\n$paymentUrl\n\nJika Anda tidak sengaja menutup halaman, gunakan link di atas untuk membayar.";
            FonnteService::sendMessage($request->customer_phone, $waMessage);

            return response()->json([
                'status'     => 'success',
                'snap_token' => $snapToken,
                'order_id'   => $orderId,
                'total_price'=> number_format($totalPrice, 0, ',', '.'),
            ]);
        } catch (\Exception $e) {
            // Kembalikan stok jika Midtrans gagal
            if ($event) {
                $event->increment('stock');
            }
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}