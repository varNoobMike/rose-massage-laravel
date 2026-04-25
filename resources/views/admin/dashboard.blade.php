@extends('layouts.admin')

@section('title', 'Overview')

@section('content')

<div class="row g-4 mb-4">

    <div class="col-md-4">
        <div class="card p-3">
            <small class="text-muted">Today's Bookings</small>
            <h3 class="fw-bold">12</h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <small class="text-muted">Active Services</small>
            <h3 class="fw-bold">24</h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <small class="text-muted">Revenue</small>
            <h3 class="fw-bold text-primary">$1,420</h3>
        </div>
    </div>

</div>

<div class="card">
    <div class="card-header fw-bold">
        Recent Appointments
    </div>

    <table class="table mb-0">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Service</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>Jane Doe</td>
                <td>Swedish Massage</td>
                <td><span class="badge bg-success">Confirmed</span></td>
                <td class="text-end">
                    <a href="#" class="text-primary">Edit</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>

@endsection