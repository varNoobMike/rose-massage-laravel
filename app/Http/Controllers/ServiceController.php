<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Spa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index(Request $request)
    {

        // Start the query
        $query = Service::query();

        // 1. Search by Name, ID, or Description
        $query->when($request->search, function ($q, $search) {
            return $q->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('id', $search)
                    ->orWhere('description', 'like', "%{$search}%");
            });
        });

        // 2. Filter by Price Range
        $query->when($request->rate, function ($q, $price) {
            return match ($price) {
                'low'  => $q->where('price', '<', 1500),
                'mid'  => $q->whereBetween('price', [1500, 3000]),
                'high' => $q->where('price', '>', 3000),
                default => $q,
            };
        });

        // 3. Filter by Duration Range
        $query->when($request->duration, function ($q, $duration) {
            return match ($duration) {
                'short'  => $q->where('duration_minutes', '<', 60),            // Less than 1 hour
                '60'     => $q->where('duration_minutes', 60),                 // Exactly 60 mins
                '90'     => $q->where('duration_minutes', 90),                 // Exactly 90 mins
                'long'   => $q->where('duration_minutes', '>', 90),            // More than 90 mins
                default  => $q,
            };
        });

        // 3. Filter by Status
        $query->when($request->status, function ($q, $status) {
            return match ($status) {
                'active'   => $q->where('status', 'active'),
                'inactive' => $q->where('status', 'inactive'),
                'all'      => $q, // no filter
                default    => $q,
            };
        }, function ($q) {
            // default when nothing selected
            return $q->where('status', 'active');
        });

        // Execute with pagination and preserve query strings for links
        $services = $query->latest()->paginate(10)->withQueryString();

        return view(
            $this->currentRoleView() . '.services.index',
            ['services' => $services]
        );
    }

    public function show(Service $service)
    {
        return view(
            $this->currentRoleView() . '.services.show',
            ['service' => $service]
        );
    }

    public function create()
    {
        return view($this->currentRoleView() . '.services.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:services,name',
            'rate' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|max:2048',
        ]);

        $spa = Spa::firstOrFail();

        /**
         * Handle image upload
         */
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('services', 'public');
        }

        /**
         * Create service
         */
        $service = Service::create([
            'spa_id' => $spa->id,
            'name' => $data['name'],
            'price' => $data['rate'],
            'duration_minutes' => $data['duration'],
            'status' => $data['status'],
            'image' => $imagePath,
        ]);

        return redirect()
            ->route('services.show', $service->id)
            ->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        return view(
            $this->currentRoleView() . '.services.edit',
            [
                'service' => $service
            ]
        );
    }

    public function update(Request $request, Service $service)
    {
        // $role = Auth::user()->role ?? '';

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:services,name,' . $service->id,
            'image' => 'nullable|image|max:2048',
            'rate' => 'required|numeric',
            'duration' => 'required|integer',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('image')) {
            /**
             * Delete old, store new
             */
            if ($service->image) Storage::disk('public')->delete($service->image);
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service->update([
            'name' => $data['name'],
            'price' => $data['rate'],
            'duration_minutes' => $data['duration'],
            'status' => $data['status'],
            'image' => $data['image'] ?? $service->image,
        ]);

        return redirect()
            ->route('services.show', $service->id)
            ->with('success', 'Service updated successfully.');
    }

    
}
