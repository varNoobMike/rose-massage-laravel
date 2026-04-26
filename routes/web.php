<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReceptionistController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TherapistController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingNotification;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

Route::get('/about', [App\Http\Controllers\ContactController::class, 'index'])
    ->name('about.index');

Route::get('/contact', [App\Http\Controllers\ContactController::class, 'index'])
    ->name('contact.index');



/**
 * Announcement Routes 
 */
Route::prefix('/announcement')->group(function () {
    Route::get('/', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/create', [AnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/{announcement}', [AnnouncementController::class, 'show'])->name('announcements.show');
    Route::get('/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit');
    Route::put('/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
});

/**
 * Booking Routes 
 */
Route::prefix('/bookings')->middleware('auth')->group(function () {
    Route::get('/', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::post('/{booking}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('/{booking}/complete', [BookingController::class, 'complete'])->name('bookings.complete');
    Route::post('/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
});

/**
 * Dashboard Route
 */
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

/**
 * Notifications Routes 
 */
Route::prefix('/notifications')->group(function () {

    Route::get('/', [NotificationController::class, 'index'])
        ->name('notifications.index');


    // mark single notification as read
    Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.read');

    // mark all as read
    Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.readAll');
});

/**
 * Receptionists
 */
Route::prefix('/receptionists')->group(function () {
    Route::get('/', [ReceptionistController::class, 'index'])->name('receptionists.index');
    Route::get('/create', [ReceptionistController::class, 'create'])->name('receptionists.create');
    Route::post('/', [ReceptionistController::class, 'store'])->name('receptionists.store');
    Route::get('/{user}', [ReceptionistController::class, 'show'])->name('receptionists.show');
    Route::get('/{user}/edit', [ReceptionistController::class, 'edit'])->name('receptionists.edit');
    Route::put('/{user}', [ReceptionistController::class, 'update'])->name('receptionists.update');
});

/**
 * Services Routes 
 */
Route::prefix('/services')->group(function () {
    Route::get('/', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/{service}', [ServiceController::class, 'show'])->name('services.show');
    Route::get('/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/{service}', [ServiceController::class, 'update'])->name('services.update');
});

/**
 * Therapists
 */
Route::prefix('/therapists')->group(function () {
    Route::get('/', [TherapistController::class, 'index'])->name('therapists.index');
    Route::get('/create', [TherapistController::class, 'create'])->name('therapists.create');
    Route::post('/', [TherapistController::class, 'store'])->name('therapists.store');
    Route::get('/{user}', [TherapistController::class, 'show'])->name('therapists.show');
    Route::get('/{user}/edit', [TherapistController::class, 'edit'])->name('therapists.edit');
    Route::put('/{user}', [TherapistController::class, 'update'])->name('therapists.update');
});

/**
 * Users Routes 
 */
Route::prefix('/users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/', [UserController::class, 'store'])->name('users.store');
    Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
});
