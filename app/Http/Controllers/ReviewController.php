<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'rating'   => 'required|integer|min:1|max:5',
            'comment'  => 'required|string|max:1000',
        ]);

        $event = Event::findOrFail($request->event_id);
        $userName = Auth::check() ? Auth::user()->name : ($request->user_name ?? 'Pengunjung');

        Review::create([
            'event_id'   => $event->id,
            'partner_id' => $event->partner_id,
            'user_id'    => Auth::id(),
            'user_name'  => $userName,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        return back()->with('success', 'Ulasan Anda berhasil dikirim!');
    }
}
