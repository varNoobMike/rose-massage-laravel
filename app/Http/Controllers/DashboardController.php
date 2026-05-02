<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Service;
use App\Models\Therapist;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if ($this->currentUserRole() === User::ROLE_CLIENT) {
            return to_route('home');
        }

        // =========================
        // BOOKING SUMMARY
        // =========================
        $totalBookings = Booking::count();

        $todayBookings = Booking::whereDate('booking_date', Carbon::today('Asia/Manila'))->count();

        $pendingBookings = Booking::where('status', 'pending')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $activeBookings = Booking::where('status', 'active')->count();
        $completedBookings = Booking::where('status', 'completed')->count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();

        // =========================
        // REVENUE SUMMARY
        // =========================
        $totalRevenue = Booking::where('status', 'completed')->sum('total_amount');

        $todayRevenue = Booking::where('status', 'completed')
            ->whereDate('booking_date', Carbon::today('Asia/Manila'))
            ->sum('total_amount');

        $weeklyRevenue = Booking::where('status', 'completed')
            ->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->sum('total_amount');

        $monthlyRevenue = Booking::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_amount');

        // =========================
        // RESOURCE SUMMARY
        // =========================
        $totalUsers = User::count();

        $activeUsers = User::where('status', 'active')->count();

        $inactiveUsers = User::where('status', 'inactive')->count();

        $pendingUsers = User::where('status', 'pending')->count();

        $totalClients = User::where('role', 'client')->count();

        $totalOwner = User::where('role', 'owner')->count();

        $totalReceptionist = User::where('role', 'receptionist')->count();

        $totalServices = Service::count();

        $totalTherapists = User::where('role', User::ROLE_THERAPIST)->count();

        // =========================
        // RECENT BOOKINGS
        // =========================
        // dd(Booking::latest()->first()->booking_date);

        $todayBookingsList = Booking::with(['client', 'items.service'])
            ->whereDate('booking_date', Carbon::today('Asia/Manila'))
            ->latest()
            ->get();

        // dd($todayBookings);

        return view('admin.dashboard', compact(
            // bookings
            'totalBookings',
            'todayBookings',
            'pendingBookings',
            'confirmedBookings',
            'activeBookings',
            'completedBookings',
            'cancelledBookings',

            // revenue
            'totalRevenue',
            'todayRevenue',
            'weeklyRevenue',
            'monthlyRevenue',

            // resources
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'pendingUsers',
            'totalClients',
            'totalOwner',
            'totalReceptionist',
            'totalServices',
            'totalTherapists',

            // activity
            'todayBookingsList'
        ));
    }
}
