<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Partner;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $events = Event::with('category')->latest()->get();
        $categories = Category::all();
        $partners = Partner::where('status', 'Aktif')->latest()->get();
        return view('welcome', compact('events', 'categories', 'partners'));
    }
}