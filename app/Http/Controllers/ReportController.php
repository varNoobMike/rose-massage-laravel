<?php

namespace App\Http\Controllers;

use App\Actions\Report\GetBookingReport;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function booking(Request $request, GetBookingReport $action)
    {
        $filters = $request->only([
            'search',
            'from',
            'to',
            'status',
            'min_amount',
            'max_amount',
            'sort_by',
            'sort_dir',
        ]);

        $query = $action->execute($filters);

        // 📊 ANALYTICS (separate cloned queries)
        $totalCount = (clone $query)->count();
        $totalSales = (clone $query)->sum('total_amount');
        $avgBooking = (clone $query)->avg('total_amount');

        $completedCount = (clone $query)->where('status', 'completed')->count();
        $pendingCount = (clone $query)->where('status', 'pending')->count();
        $cancelledCount = (clone $query)->where('status', 'cancelled')->count();

        $completionRate = $totalCount > 0
            ? round(($completedCount / $totalCount) * 100, 2)
            : 0;

        $bookings = $query->paginate(10)->withQueryString();

        return view($this->currentRoleView() . '.reports.bookings', compact(
            'bookings',
            'totalCount',
            'totalSales',
            'avgBooking',
            'completedCount',
            'pendingCount',
            'cancelledCount',
            'completionRate'
        ));
    }

}