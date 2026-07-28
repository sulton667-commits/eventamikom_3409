<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Event;
use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PartnerProfileController extends Controller
{
    public function show($id = null)
    {
        $partner = $id ? Partner::find($id) : Partner::first();

        if (!$partner) {
            $partner = Partner::create([
                'name'     => 'HMSSI Amikom',
                'category' => 'Himpunan Mahasiswa Sistem & Sains Informasi Amikom',
                'status'   => 'Aktif'
            ]);
        }

        $events         = Event::where('partner_id', $partner->id)->get();
        $upcomingEvents = Event::where('partner_id', $partner->id)->where('date', '>=', now())->get();
        $pastEvents     = Event::where('partner_id', $partner->id)->where('date', '<', now())->get();

        $reviews      = Review::with('event')->where('partner_id', $partner->id)->latest()->get();
        $totalReviews = $reviews->count();
        $avgRating    = $totalReviews > 0 ? round($reviews->avg('rating'), 1) : 4.0;
        $totalEvents  = $events->count();
        
        $ticketsSold  = Transaction::whereIn('event_id', $events->pluck('id'))
                            ->whereIn('status', ['settlement', 'success'])
                            ->count();
        if ($ticketsSold == 0) {
            $ticketsSold = 9; // Fallback display untuk demo
        }

        // Distribusi Bintang (1-5)
        $distribution = [];
        for ($star = 5; $star >= 1; $star--) {
            $count = $reviews->where('rating', $star)->count();
            $percentage = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
            $distribution[$star] = [
                'count'      => $count,
                'percentage' => $percentage
            ];
        }

        return view('partner-profile', compact(
            'partner',
            'events',
            'upcomingEvents',
            'pastEvents',
            'reviews',
            'totalReviews',
            'avgRating',
            'totalEvents',
            'ticketsSold',
            'distribution'
        ));
    }
}
