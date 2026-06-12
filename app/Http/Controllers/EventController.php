<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show($id = null)
    {
        $event = $id ? Event::with('category')->findOrFail($id) : Event::with('category')->first();
        return view('event-detail', compact('event'));
        $event = $id ? Event::with(['category', 'partner'])->findOrFail($id) : Event::with(['category', 'partner'])->first();
        $partners = \App\Models\Partner::where('is_active', true)->get();
        return view('event-detail', compact('event', 'partners'));
    }

    public function checkout()
    {
        return view('checkout');
    }

}