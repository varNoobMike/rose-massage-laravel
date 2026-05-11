<?php

use App\Http\Controllers\AccountSecurityController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceptionistController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TherapistAssignmentController;
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


Route::get('clear-session', function () {
    Auth::logout();
    return to_route('login');
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
 * Authentication Routes
 */
Route::controller(AuthController::class)->group(function () {
    // Guest Routes (Login & Register)
    Route::middleware('guest')->group(function () {
        // login
        Route::get('/login', 'showLogin')->name('login');
        Route::post('/login', 'login')->name('login.post');
        // register
        Route::get('/register', 'showRegister')->name('register');
        Route::post('/register', 'register')->name('register.post');
    });

    // Authenticated Routes
    Route::middleware('auth')->group(function () {
        // logout
        Route::post('/logout', 'logout')->name('logout');
    });
});


/**
 * Home Route
 */
Route::controller(App\Http\Controllers\HomeController::class)
    ->group(function () {
        Route::get('/', 'index')
            ->name('home');
        Route::get('/home', 'index');
        Route::get('/user', 'index')
            ->name('user');
        Route::get('/user/home', 'index')
            ->name('user.home');
    });


/**
 * About and Contact Route (public or client) only
 */
Route::prefix('')
    ->middleware(['block.admin.owner.staff'])
    ->group(function () {
        // about
        Route::get('/about', [App\Http\Controllers\AboutController::class, 'index'])
            ->name('about.index');
        // contact
        Route::get('/contact', [App\Http\Controllers\ContactController::class, 'index'])
            ->name('contact.index');
    });


/**
 * Activity Log Routes (admin, owner, receptionist) only
 */
Route::prefix('/activity-logs')
    ->middleware(['auth', 'role:admin,owner,receptionist'])
    ->name('activity-logs.')
    ->group(function () {
        // create
        Route::get('/create', [ActivityLogController::class, 'create'])
            ->name('create');
        Route::post('/', [ActivityLogController::class, 'store'])
            ->name('store');
        // read
        Route::get('/', [ActivityLogController::class, 'index'])
            ->name('index');
        Route::get('/{log}', [ActivityLogController::class, 'show'])
            ->name('show');
    });


/**
 * Announcement Routes 
 */
Route::prefix('/announcements')
    ->name('announcements.')
    ->middleware('auth')
    ->group(function () {

        /**
         * read access (admin, owner, client) only
         */
        Route::middleware('role:admin,owner,client')->group(function () {
            // read
            Route::get('/', [AnnouncementController::class, 'index'])
                ->name('index');
            Route::get('/{announcement}', [AnnouncementController::class, 'show'])
                ->name('show');
        });

        /**
         * write access (admin, owner) only
         */
        Route::middleware('role:admin,owner')->group(function () {
            // create
            Route::get('/create', [AnnouncementController::class, 'create'])
                ->name('create');
            Route::post('/', [AnnouncementController::class, 'store'])
                ->name('store');
            // update
            Route::get('/{announcement}/edit', [AnnouncementController::class, 'edit'])
                ->name('edit');
            Route::put('/{announcement}', [AnnouncementController::class, 'update'])
                ->name('update');
            // delete
            Route::delete('/{announcement}', [AnnouncementController::class, 'destroy'])
                ->name('destroy');
        });
    });


/**
 * Booking Routes
 */
Route::prefix('/bookings')
    ->name('bookings.')
    ->middleware('auth')
    ->group(function () {

        /**
         *  (admin, owner, receptionist, client) only
         */
        Route::middleware('role:admin,owner,receptionist,client')->group(function () {
            // read
            Route::get('/', [BookingController::class, 'index'])
                ->name('index');
            Route::get('/{booking}', [BookingController::class, 'show'])
                ->name('show');
            // create
            Route::get('/create', [BookingController::class, 'create'])
                ->name('create');
            Route::post('/', [BookingController::class, 'store'])
                ->name('store');
            // cancel 
            Route::post('/{booking}/cancel', [BookingController::class, 'cancel'])
                ->name('cancel');
        });

        /**
         * (admin, owner, receptionist) only
         */
        Route::middleware('role:admin,owner,receptionist')->group(function () {
            // update
            Route::get('/{booking}/edit', [BookingController::class, 'edit'])
                ->name('edit');
            Route::put('/{booking}', [BookingController::class, 'update'])
                ->name('update');
            // confirm
            Route::post('/{booking}/confirm', [BookingController::class, 'confirm'])
                ->name('confirm');
            // reject
            Route::post('/{booking}/reject', [BookingController::class, 'reject'])
                ->name('reject');
        });
    });


/**
 * Therapist Assignment
 * 
 */
Route::prefix('/therapist-assignments')
    ->middleware(['auth', 'role:admin,owner,receptionist'])
    ->name('therapist-assignments.')
    ->group(function () {
        //read
        Route::get('/{booking}', [TherapistAssignmentController::class, 'index'])
            ->name('index');
        // update
        Route::put('/{booking}', [TherapistAssignmentController::class, 'update'])
            ->name('update');
    });


/**
 * Clients
 */
Route::prefix('/clients')
    ->name('clients.')
    ->middleware('auth')
    ->group(function () {

        /**
         * read access (admin, owner, receptionist) only
         * 
         */
        Route::middleware('role:admin,owner,receptionist')->group(function () {

            Route::get('/', [ClientController::class, 'index'])
                ->name('index');

            Route::get('/{user}', [ClientController::class, 'show'])
                ->name('show');
        });

        /**
         * write access (admin and owner) only
         * 
         */
        Route::middleware('role:admin,owner')->group(function () {

            Route::get('/create', [ClientController::class, 'create'])
                ->name('create');

            Route::post('/', [ClientController::class, 'store'])
                ->name('store');

            /*
            Route::get('/{user}/edit', [ClientController::class, 'edit'])
                ->name('edit');
            Route::put('/{user}', [ClientController::class, 'update'])
                ->name('update');
            */
        });
    });

/**
 * Dashboard Route
 */
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'role:admin,owner,receptionist'])
    ->name('dashboard');


/**
 * Notification Routes 
 */
Route::prefix('/notifications')
    ->middleware('auth')
    ->name('notifications.')
    ->group(function () {
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
 * Profile Routes
 */
Route::prefix('/profile')
    ->middleware('auth')
    ->name('profile.')
    ->group(function () {
        // read
        Route::get('/', [ProfileController::class, 'index'])
            ->name('index');
        // update
        Route::get('/edit', [ProfileController::class, 'edit'])
            ->name('edit');
        Route::put('/', [ProfileController::class, 'update'])
            ->name('update');
    });


/**
 * Account Security Routes
 */
Route::prefix('/account')
    ->middleware('auth')
    ->name('account.')
    ->group(function () {
        // security dashboard
        Route::get('/security', [AccountSecurityController::class, 'index'])
            ->name('security');
        // password edit form
        Route::get('/password', [AccountSecurityController::class, 'editPassword'])
            ->name('password.edit');
        // password update
        Route::put('/password', [AccountSecurityController::class, 'updatePassword'])
            ->name('password.update');
    });


/**
 * Receptionist Routes (admin, owner) only
 */
Route::prefix('/receptionists')
    ->name('receptionists.')
    ->middleware(['auth', 'role:admin,owner'])
    ->group(function () {
        // create
        Route::get('/create', [ReceptionistController::class, 'create'])
            ->name('create');
        Route::post('/', [ReceptionistController::class, 'store'])
            ->name('store');
        // read
        Route::get('/', [ReceptionistController::class, 'index'])
            ->name('index');
        Route::get('/{user}', [ReceptionistController::class, 'show'])
            ->name('show');
        // update
        Route::get('/{user}/edit', [ReceptionistController::class, 'edit'])
            ->name('edit');
        Route::put('/{user}', [ReceptionistController::class, 'update'])
            ->name('update');
    });


/**
 * Reports (admin, owner) only
 */
Route::prefix('/reports')
    ->middleware(['auth', 'role:admin,owner'])
    ->name('reports.')
    ->group(function () {
        // bookings
        Route::get('/bookings', [ReportController::class, 'booking'])
            ->name('bookings');
    });


/**
 * Review Routes
 */
Route::prefix('/reviews')
    ->name('reviews.')
    ->group(function () {

        /**
         * (admin, owner, client)
         */
        Route::middleware(['role:admin,owner,client'])
            ->group(function () {
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
                Route::get('/{review}/edit', [ReviewController::class, 'edit'])
                    ->name('edit');
                Route::put('/{review}', [ReviewController::class, 'update'])
                    ->name('update');
                // delete
                Route::delete('/{review}', [ReviewController::class, 'destroy'])
                    ->name('destroy');
            });

        /**
         *  (admin, owner) only
         */
        Route::middleware(['role:admin,owner'])
            ->group(function () {
                // approve
                Route::post('/{review}/approve', [ReviewController::class, 'approve'])
                    ->name('approve');
                // reject
                Route::post('/{review}/reject', [ReviewController::class, 'reject'])
                    ->name('reject');
            });
    });


/**
 * Services Routes 
 */
Route::prefix('/services')
    ->name('services.')
    ->group(function () {

        /**
         * read access (public routes)
         */
        Route::get('/', [ServiceController::class, 'index'])
            ->name('index');

        Route::get('/{service}', [ServiceController::class, 'show'])
            ->name('show');

        /**
         * write access (admin and owner) only
         */
        Route::middleware(['auth', 'role:admin,owner'])->group(function () {
            // create
            Route::get('/create', [ServiceController::class, 'create'])
                ->name('create');
            Route::post('/', [ServiceController::class, 'store'])
                ->name('store');
            // update
            Route::get('/{service}/edit', [ServiceController::class, 'edit'])
                ->name('edit');
            Route::put('/{service}', [ServiceController::class, 'update'])
                ->name('update');
        });
    });


/**
 * Therapists
 */
Route::prefix('/therapists')
    ->name('therapists.')
    ->middleware('auth')
    ->group(function () {

        /**
         * read access (admin, owner, receptionist) only
         */
        Route::middleware('role:admin,owner,receptionist')->group(function () {
            // read
            Route::get('/', [TherapistController::class, 'index'])
                ->name('index');
            Route::get('/{user}', [TherapistController::class, 'show'])
                ->name('show');
        });

        /**
         * write access (admin, owner) only
         */
        Route::middleware('role:admin,owner')->group(function () {
            // create
            Route::get('/create', [TherapistController::class, 'create'])
                ->name('create');
            Route::post('/', [TherapistController::class, 'store'])
                ->name('store');
            // update
            Route::get('/{user}/edit', [TherapistController::class, 'edit'])
                ->name('edit');
            Route::put('/{user}', [TherapistController::class, 'update'])
                ->name('update');
        });
    });


/**
 * Users Routes 
 */
Route::prefix('/users')
    ->middleware(['auth', 'role:admin'])
    ->name('users.')
    ->group(function () {
        // create
        Route::get('/create', [UserController::class, 'create'])
            ->name('create');
        Route::post('/', [UserController::class, 'store'])
            ->name('store');
        // read
        Route::get('/', [UserController::class, 'index'])
            ->name('index');
        Route::get('/{user}', [UserController::class, 'show'])
            ->name('show');
        // update
        Route::get('/{user}/edit', [UserController::class, 'edit'])
            ->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])
            ->name('update');
    });
