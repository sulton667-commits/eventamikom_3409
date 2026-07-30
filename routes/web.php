<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\TransactionsController;
use App\Http\Controllers\EventController as PublicEventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserAuthController;
use Illuminate\Support\Facades\Route;


// ===========================
// HALAMAN PUBLIK
// ===========================
Route::get('/', [HomeController::class, 'index']);

Route::get('/kontak', function () {
    return view('contact');
});

Route::get('/profil', function () {
    return view('profil');
});

Route::get('/katalog', function () {
    return view('katalog');
});

Route::get('/bantuan', function () {
    return view('bantuan');
});

Route::get('/event-detail/{id?}', [PublicEventController::class, 'show']);
Route::get('/partner-profile/{id?}', [\App\Http\Controllers\PartnerProfileController::class, 'show'])->name('partner.profile.public');
Route::post('/review/store', [\App\Http\Controllers\ReviewController::class, 'store'])->name('review.store');
Route::get('/checkout', [PublicEventController::class, 'checkout']);
Route::post('/checkout/process', [PublicEventController::class, 'processCheckout'])->name('checkout.process');
Route::get('/ticket', [TicketController::class, 'show']);
Route::get('/ticket/send-email', [TicketController::class, 'sendEmail'])->name('ticket.send-email');
Route::post('/midtrans/callback', [\App\Http\Controllers\MidtransWebhookController::class, 'handle'])->name('midtrans.callback');


// ===========================
// AUTENTIKASI CUSTOMER (USER)
// ===========================
Route::get('/login', [UserAuthController::class, 'showLoginUser'])->name('user.login');
Route::post('/login', [UserAuthController::class, 'loginUser'])->name('user.login.submit');
Route::get('/register', [UserAuthController::class, 'showRegister'])->name('user.register');
Route::post('/register', [UserAuthController::class, 'register'])->name('user.register.submit');
Route::get('/auth/google', [UserAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [UserAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::post('/logout', [UserAuthController::class, 'logout'])->name('user.logout');


// ===========================
// AUTENTIKASI & DASHBOARD PARTNER
// ===========================
Route::prefix('partner')->name('partner.')->group(function () {
    Route::get('/login', [UserAuthController::class, 'showLoginPartner'])->name('login');
    Route::post('/login', [UserAuthController::class, 'loginPartner'])->name('login.submit');
    Route::get('/register', [UserAuthController::class, 'showRegisterPartner'])->name('register');
    Route::post('/register', [UserAuthController::class, 'registerPartner'])->name('register.submit');
    Route::get('/dashboard', [\App\Http\Controllers\Partner\PartnerDashboardController::class, 'index'])->name('dashboard');
    Route::post('/scanner/check', [\App\Http\Controllers\Partner\PartnerDashboardController::class, 'checkIn'])->name('scanner.check');
});


// ===========================
// AUTENTIKASI ADMIN
// ===========================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('events', EventController::class);
    Route::resource('partners', PartnerController::class);
    Route::get('/transactions', [TransactionsController::class, 'index'])->name('transactions');
    Route::get('/categories', [CategoriesController::class, 'index'])->name('categories');
    Route::get('/categories/create', [CategoriesController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoriesController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoriesController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoriesController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoriesController::class, 'destroy'])->name('categories.destroy');

    Route::middleware(['admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('events', EventController::class);
        Route::resource('partners', PartnerController::class);
        Route::get('/transactions', [TransactionsController::class, 'index'])->name('transactions');
        Route::get('/scanner', [\App\Http\Controllers\Admin\ScannerController::class, 'index'])->name('scanner');
        Route::post('/scanner/check', [\App\Http\Controllers\Admin\ScannerController::class, 'checkIn'])->name('scanner.check');
        Route::get('/categories', [CategoriesController::class, 'index'])->name('categories');
        Route::get('/categories/create', [CategoriesController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoriesController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoriesController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoriesController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoriesController::class, 'destroy'])->name('categories.destroy');
    });
});