<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actions\ActivityLog\GetFilteredLogs;
use App\Models\ActivityLog;
use App\Models\User;

class ActivityLogController extends Controller
{

    public function index(Request $request, GetFilteredLogs $action)
    {
        $logs = $action->execute(
            $request->only([
                'search',
                'action',
                'user_id',
                'subject_type',
                'subject_id',
                'from',
                'to',
            ]),
            $this->currentUserRole(),
            auth()->id()
        );

        return view(
            $this->currentRoleView() . '.activity-logs.index',
            compact('logs')
        );
    }

    public function show(ActivityLog $log) {
        return view($this->currentRoleView() . '.activity-logs.show', compact('log'));
    }
}
