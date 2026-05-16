<?php

namespace App\Actions\OperatingHour;

use App\Models\OperatingHour;
use App\Models\User;

class GetFilteredOperatingHours
{
    public function execute(array $filters, ?User $user = null)
    {
        $search = $filters['search'] ?? null;
        $dayOfWeek = $filters['day_of_week'] ?? null;
        $period = $filters['period'] ?? null;
        $isClosed = $filters['is_closed'] ?? null;

        $query = OperatingHour::query();

        // search by day
        $query->when($search, function ($q, $search) {
            $q->where('day_of_week', 'like', "%{$search}%");
        });

        // exact day
        $query->when($dayOfWeek, function ($q, $dayOfWeek) {
            $q->where('day_of_week', $dayOfWeek);
        });

        // open / closed filter
        $query->when($isClosed !== null && $isClosed !== '', function ($q) use ($isClosed) {
            $q->where('is_closed', (bool) $isClosed);
        });

        // AM / PM filter (only open days)
        $query->when($period, function ($q, $period) {

            $q->where('is_closed', false);

            if ($period === 'am') {
                $q->whereTime('start_time', '<', '12:00:00');
            }

            if ($period === 'pm') {
                $q->whereTime('start_time', '>=', '12:00:00');
            }
        });

        return $query
            ->orderBy('day_order')
            ->paginate(10)
            ->withQueryString();
    }
}
