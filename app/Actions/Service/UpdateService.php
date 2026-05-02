<?php

namespace App\Actions\Service;

use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateService
{
    public function execute(Service $service, array $data): Service
    {
        return DB::transaction(function () use ($service, $data) {

            /**
             * Handle image replacement
             */
            $imagePath = $service->image;

            if (isset($data['image'])) {

                // delete old image
                if ($service->image) {
                    Storage::disk('public')->delete($service->image);
                }

                // store new image
                $imagePath = $data['image']->store('services', 'public');
            }

            /**
             * Update service
             */
            $service->update([
                'name' => $data['name'],
                'price' => $data['rate'],
                'duration_minutes' => $data['duration'],
                'status' => $data['status'],
                'image' => $imagePath,
            ]);

            return $service;
        });
    }
}
