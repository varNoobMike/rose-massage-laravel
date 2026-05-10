<?php

namespace App\Http\Controllers;

use App\Actions\Service\GetFilteredServices;
use App\Actions\Service\StoreService;
use App\Actions\Service\UpdateService;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index(Request $request, GetFilteredServices $action)
    {
        $filters = $request->only([
            'search',
            'price_from',
            'price_to',
            'duration_from',
            'duration_to',
            'status',
        ]);

        // fetch filtered services
        $services = $action->execute($filters, Auth::user());

        // basic filters state
        $hasBasicFilters =
            !empty($filters['search']) ||
            !empty($filters['price_from']) ||
            !empty($filters['price_to']) ||
            !empty($filters['duration_from']) ||
            !empty($filters['duration_to']) ||
            !empty($filters['status']);

        // global filters state
        $hasFilters = $hasBasicFilters;

        return view(
            $this->currentRoleView() . '.services.index',
            compact('services', 'hasFilters', 'filters')
        );
    }

    public function show(Service $service)
    {
        return view(
            $this->currentRoleView() . '.services.show',
            compact('service')
        );
    }

    public function create()
    {
        return view($this->currentRoleView() . '.services.create');
    }

    public function store(Request $request, StoreService $action)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:services,name',
            'rate' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|max:2048',
        ]);

        $service = $action->execute($validated);

        return to_route('services.show', $service->id)
            ->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        return view(
            $this->currentRoleView() . '.services.edit',
            compact('service')
        );
    }

    public function update(Request $request, Service $service, UpdateService $action)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:services,name,' . $service->id,
            'image' => 'nullable|image|max:2048',
            'rate' => 'required|numeric',
            'duration' => 'required|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $service = $action->execute($service, $validated);

        return to_route('services.show', $service->id)
            ->with('success', 'Service updated successfully.');
    }
}
