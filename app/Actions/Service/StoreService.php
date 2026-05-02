<?php

namespace App\Actions\Service;

use App\Models\Service;
use App\Models\Spa;
use Illuminate\Support\Facades\DB;

class StoreService
{
    public function execute(array $data): Service
    {
        return DB::transaction(function () use ($data) {

            // Get spa record
            $spa = Spa::firstOrFail();

            // Default image
            $imagePath = null;

            // Upload image if exists
            if ($data['image']) {
                $imagePath = $data['image']->store('services', 'public');
            }

            // Create service
            return Service::create([
                'spa_id' => $spa->id,
                'name' => $data['name'],
                'price' => $data['rate'],
                'duration_minutes' => $data['duration'],
                'status' => $data['status'],
                'image' => $imagePath,
            ]);
        });
    }
}
