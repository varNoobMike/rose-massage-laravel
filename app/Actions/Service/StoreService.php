<?php

namespace App\Actions\Service;

use App\Models\Service;
use App\Models\Spa;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class StoreService
{
    public function execute(array $data): Service
    {
        return DB::transaction(function () use ($data) {

            // get spa record
            $spa = Spa::firstOrFail();

            // default image
            $imagePath = null;

            // upload image if exists
            if (($data['image'] ?? null) instanceof UploadedFile) {
                $imagePath = $data['image']->store('services', 'public');
            }

            // create service
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
