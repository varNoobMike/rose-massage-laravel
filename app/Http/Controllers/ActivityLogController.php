<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actions\ActivityLog\GetFilteredLogs;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    public function index(Request $request, GetFilteredLogs $action)
    {
        $filters = $request->only([
            'search',
            'action',
            'subject_type',
            'subject_id',
            'date_from',
            'date_to',
        ]);

        // fetch filtered logs
        $logs = $action->execute(
            $filters,
            Auth::user()
        );

        // basic filters state
        $hasBasicFilters =
            !empty($filters['search']) ||
            !empty($filters['action']) ||
            !empty($filters['subject_type']) ||
            !empty($filters['subject_id']) ||
            !empty($filters['date_from']) ||
            !empty($filters['date_to']);

        $hasFilters = $hasBasicFilters;

        return view(
            $this->currentRoleView() . '.activity-logs.index',
            compact('logs', 'filters', 'hasFilters')
        );
    }

    public function show(ActivityLog $log)
    {
        return view($this->currentRoleView() . '.activity-logs.show', compact('log'));
    }
}
