@if (session('success'))
<div class="col-12">
    <div class="alert alert-success alert-dismissible fade show shadow-sm border d-flex align-items-center gap-2 auto-dismiss" role="alert">
        <i class="bi bi-check-circle-fill"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

@if (session('info'))
<div class="col-12">
    <div class="alert alert-info alert-dismissible fade show shadow-sm border d-flex align-items-center gap-2 auto-dismiss" role="alert">
        <i class="bi bi-info-circle-fill"></i>
        <div>{{ session('info') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

@if (session('warning'))
<div class="col-12">
    <div class="alert alert-warning alert-dismissible fade show shadow-sm border d-flex align-items-center gap-2 auto-dismiss" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>{{ session('warning') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

@if (session('error'))
<div class="col-12">
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border d-flex align-items-center gap-2 auto-dismiss" role="alert">
        <i class="bi bi-x-circle-fill"></i>
        <div>{{ session('error') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif