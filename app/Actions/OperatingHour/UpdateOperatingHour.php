<?php

namespace App\Actions\OperatingHour;

use App\Models\OperatingHour;

class UpdateOperatingHour
{
    public function execute(OperatingHour $operatingHour, array $data): OperatingHour
    {
        
        $operatingHour->update([
            'is_closed'  => $data['is_closed'] ?? $operatingHour->is_closed,
            'start_time' => $data['start_time'] ?? $operatingHour->start_time,
            'end_time'   => $data['end_time'] ?? $operatingHour->end_time,
        ]);

        return $operatingHour;
    }
}
