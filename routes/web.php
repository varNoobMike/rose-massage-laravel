<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReceptionistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TherapistController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingNotification;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


/**
 * Test Route - For development and testing purposes only. Remove or comment out in production.
 */
Route::get('/test-email', function () {
    $data = [
        'customer_name' => 'Test User',
        'service' => 'Test Service',
        'date' => '2026-10-15',
    ];

    Mail::to('lindseybongcawel4@gmail.com')->send(new BookingNotification($data));
    return 'This is a test email route.';
});


// test xendit api key
Route::get('/test-xendit', function () {

    $response = Http::withBasicAuth(env('XENDIT_SECRET_KEY'), '')
        ->get('https://api.xendit.co/v2/invoices');

    return $response->json();
});

Route::get('/create-test-invoice', function () {

    $response = Http::withBasicAuth(env('XENDIT_SECRET_KEY'), '')
        ->post('https://api.xendit.co/v2/invoices', [
            'external_id' => 'test-' . time(),
            'amount' => 1000,
            'payer_email' => 'test@gmail.com',
            'description' => 'Test invoice'
        ]);

    return $response->json();
});

/***
 * Session Reset Route - For development and testing purposes only. This route logs out the user and clears the session. Remove or comment out in production to prevent unauthorized access.
 */
Route::get('/reset-session', function () {
    Auth::logout();
    session()->flush();
    return 'Session reset. You have been logged out.';
});


/**
 * Built in Laravel Email Verification Routes - These routes handle the email verification process, including showing the verification notice, processing the verification link, and resending the verification email. They are protected by appropriate middleware to ensure only authenticated users can access them.
 */
// 1. The page that tells the user "Check your email!"
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// 2. The link the user clicks in their email
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/user/home'); // Redirect to user home after success
})->middleware(['auth', 'signed'])->name('verification.verify');

// 3. The "Resend" button logic
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


/**
 * Home Route - The landing page of the application, accessible to all users. 
 */
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);


/**
 * Authentication Routes - Grouped by AuthController and protected by appropriate middleware for guests and authenticated users.
 */
Route::controller(AuthController::class)->group(function () {

    // Guest Routes (Login & Register)
    Route::middleware('guest')->group(function () {
        Route::get('/login', 'showLogin')->name('login');
        Route::post('/login', 'login')->name('login.post');

        Route::get('/register', 'showRegister')->name('register');
        Route::post('/register', 'register')->name('register.post');
    });

    // Authenticated Routes
    Route::middleware('auth')->group(function () {
        Route::post('/logout', 'logout')->name('logout');
    });
});


Route::get('/user', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('user');

Route::get('/user/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('user.home');

Route::get('/about', [App\Http\Controllers\AboutController::class, 'index'])
    ->name('about.index');

Route::get('/contact', [App\Http\Controllers\ContactController::class, 'index'])
    ->name('contact.index');



/**
 * Announcement Routes 
 */
Route::prefix('/announcements')->middleware('auth')->name('announcements.')->group(function () {
    // create
    Route::get('/create', [AnnouncementController::class, 'create'])->name('create');
    Route::post('/', [AnnouncementController::class, 'store'])->name('store');
    // read
    Route::get('/', [AnnouncementController::class, 'index'])->name('index'); 
    Route::get('/{announcement}', [AnnouncementController::class, 'show'])->name('show');
    // update
    Route::get('/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('edit');
    Route::put('/{announcement}', [AnnouncementController::class, 'update'])->name('update');
});


/**
 * Booking Routes
 */
Route::prefix('/bookings')->middleware('auth')->name('bookings.')->group(function () {
    // create
    Route::get('/create', [BookingController::class, 'create'])->name('create');
    Route::post('/', [BookingController::class, 'store'])->name('store');
    //read
    Route::get('/', [BookingController::class, 'index'])->name('index');
    // update
    Route::get('/{booking}', [BookingController::class, 'show'])->name('show');
    Route::get('/{booking}/edit', [BookingController::class, 'edit'])->name('edit');
    Route::put('/{booking}', [BookingController::class, 'update'])->name('update');
    // custom
    Route::post('/{booking}/confirm', [BookingController::class, 'confirm'])->name('confirm');
    Route::post('/{booking}/complete', [BookingController::class, 'complete'])->name('complete');
    Route::post('/{booking}/cancel', [BookingController::class, 'cancel'])->name('cancel');
});


/**
 * Clients
 */
Route::prefix('/clients')->middleware('auth')->name('clients.')->group(function () {
    Route::get('/', [ClientController::class, 'index'])->name('index');
    Route::get('/create', [ClientController::class, 'create'])->name('create');
    Route::post('/', [ClientController::class, 'store'])->name('store');
    Route::get('/{user}', [ClientController::class, 'show'])->name('show');
    Route::get('/{user}/edit', [ClientController::class, 'edit'])->name('edit');
    Route::put('/{user}', [ClientController::class, 'update'])->name('update');
});


/**
 * Dashboard Route
 */
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');


/**
 * Notification Routes 
 */
Route::prefix('/notifications')->middleware('auth')->name('notifications.')->group(function () {
    // read
    Route::get('/', [NotificationController::class, 'index'])
        ->name('index');
    // mark as read
    Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])
        ->name('read');
    // mark as read all
    Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])
        ->name('readAll');
});


/**
 * Receptionist Routes
 */
    Route::prefix('/receptionists')->middleware('auth')->name('receptionists.')->group(function () {
    Route::get('/', [ReceptionistController::class, 'index'])->name('index');
    Route::get('/create', [ReceptionistController::class, 'create'])->name('create');
    Route::post('/', [ReceptionistController::class, 'store'])->name('store');
    Route::get('/{user}', [ReceptionistController::class, 'show'])->name('show');
    Route::get('/{user}/edit', [ReceptionistController::class, 'edit'])->name('edit');
    Route::put('/{user}', [ReceptionistController::class, 'update'])->name('update');
});


/**
 * Review Routes 
 */
Route::prefix('/reviews')->name('reviews.')->group(function () {
    // create
    Route::get('/create/{booking}', [ReviewController::class, 'create'])
        ->name('create');
    Route::post('/{booking}', [ReviewController::class, 'store'])
        ->name('store');
    // read
    Route::get('/', [ReviewController::class, 'index'])
        ->name('index');
    Route::get('/{review}', [ReviewController::class, 'show'])
        ->name('show');
    // update
    Route::get('/edit/{review}', [ReviewController::class, 'edit'])
        ->name('edit');
    Route::put('/{review}', [ReviewController::class, 'update'])
        ->name('update');
    // delete
    Route::delete('/{review}', [ReviewController::class, 'destroy'])
        ->name('destroy');
    // approve
    Route::post('/{review}/approve', [ReviewController::class, 'approve'])
        ->name('approve');
    // reject
    Route::post('/{review}/reject', [ReviewController::class, 'reject'])
        ->name('reject');
});


/**
 * Services Routes  , (note: needs access control improvement)
 */
Route::prefix('/services')->name('services.')->group(function () {
    // create
    Route::get('/create', [ServiceController::class, 'create'])->name('create')->middleware('auth');
    Route::post('/', [ServiceController::class, 'store'])->name('store')->middleware('auth');
    // read
    Route::get('/', [ServiceController::class, 'index'])->name('index');
    Route::get('/{service}', [ServiceController::class, 'show'])->name('show')->middleware('auth');
    // update
    Route::get('/{service}/edit', [ServiceController::class, 'edit'])->name('edit')->middleware('auth');
    Route::put('/{service}', [ServiceController::class, 'update'])->name('update')->middleware('auth');
});


/**
 * Therapists
 */
Route::prefix('/therapists')->middleware('auth')->name('therapists.')->group(function () {
    // create
    Route::get('/create', [TherapistController::class, 'create'])->name('create');
    Route::post('/', [TherapistController::class, 'store'])->name('store');
    // read
    Route::get('/', [TherapistController::class, 'index'])->name('index');
    Route::get('/{user}', [TherapistController::class, 'show'])->name('show');
    // update
    Route::get('/{user}/edit', [TherapistController::class, 'edit'])->name('edit');
    Route::put('/{user}', [TherapistController::class, 'update'])->name('update');
});


/**
 * Users Routes 
 */
Route::prefix('/users')->middleware('auth')->name('users.')->group(function () {
    // create
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/', [UserController::class, 'store'])->name('store');
    // read
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/{user}', [UserController::class, 'show'])->name('show');
    // update
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('update');
});


