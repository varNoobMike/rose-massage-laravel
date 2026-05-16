<?php

namespace App\Http\Controllers;

use App\Actions\OperatingHour\GetFilteredOperatingHours;
use App\Actions\OperatingHour\UpdateOperatingHour;
use App\Models\OperatingHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperatingHourController extends Controller
{
    public function index(Request $request, GetFilteredOperatingHours $action)
    {
        $filters = $request->only([
            'search',
            'day_of_week',
            'period',
            'is_closed',
        ]);

        // fetch filtered operating hours
        $operatingHours = $action->execute($filters, Auth::user());

        // filters state
        $hasBasicFilters =
            !empty($filters['search']) ||
            !empty($filters['day_of_week']) ||
            !empty($filters['period']) ||
            ($filters['is_closed'] ?? '') !== '';

        $hasFilters = $hasBasicFilters;

        return view(
            $this->currentRoleView() . '.operating-hours.index',
            compact('operatingHours', 'hasFilters', 'filters')
        );
    }

    public function show(OperatingHour $operatingHour)
    {
        return view(
            $this->currentRoleView() . '.operating-hours.show',
            compact('operatingHour')
        );
    }

    public function edit(OperatingHour $operatingHour)
    {
        return view(
            $this->currentRoleView() . '.operating-hours.edit',
            compact('operatingHour')
        );
    }

    public function update(Request $request, OperatingHour $operatingHour, UpdateOperatingHour $action)
    {
        $validated = $request->validate([
            'start_time' => 'nullable|date_format:H:i',
            'end_time'   => 'nullable|date_format:H:i|after:start_time',
            'is_closed'  => 'nullable|boolean',
        ]);

        // normalize checkbox (important)
        $validated['is_closed'] = $request->has('is_closed') && $request->is_closed == 1;

        $action->execute($operatingHour, $validated);

        return to_route('settings.operating-hours.show', $operatingHour->id)
            ->with('success', 'Operating hour updated successfully.');
    }
}
