<?php

namespace App\Http\Controllers;

use App\Actions\Report\GetBookingReport;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends Controller
{
    public function booking(Request $request, GetBookingReport $action)
    {
        $filters = $request->only([
            'search',
            'date_from',
            'date_to',
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

    public function exportBooking(Request $request, GetBookingReport $action)
    {
        $filters = $request->only([
            'search',
            'date_from',
            'date_to',
            'status',
            'min_amount',
            'max_amount',
            'sort_by',
            'sort_dir',
        ]);

        // SAME query as report (important)
        $bookings = $action->execute($filters)->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // headers
        $sheet->setCellValue('A1', 'Customer');
        $sheet->setCellValue('B1', 'Date');
        $sheet->setCellValue('C1', 'Status');
        $sheet->setCellValue('D1', 'Total Amount');

        $row = 2;

        foreach ($bookings as $b) {
            $sheet->setCellValue("A$row", $b->client->name ?? 'N/A');
            $sheet->setCellValue("B$row", $b->created_at->format('Y-m-d'));
            $sheet->setCellValue("C$row", $b->status);
            $sheet->setCellValue("D$row", $b->total_amount);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'booking-report.xlsx');
    }
}
