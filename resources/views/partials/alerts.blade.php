@if (session('success'))
    <div class="col-12">
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

@if (session('info'))
    <div class="col-12">
        <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

@if (session('warning'))
    <div class="col-12">
        <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="col-12">
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif
