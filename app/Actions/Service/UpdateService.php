<?php

namespace App\Actions\Service;

use App\Models\Service;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateService
{
    public function execute(Service $service, array $data): Service
    {
        return DB::transaction(function () use ($service, $data) {

            // existing image
            $imagePath = $service->image;

            /**
             * handle image replacement
             */
            if (($data['image'] ?? null) instanceof UploadedFile) {

                // delete old image
                if ($service->image) {
                    Storage::disk('public')->delete($service->image);
                }

                // store new image
                $imagePath = $data['image']->store('services', 'public');
            }

            // update service
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
