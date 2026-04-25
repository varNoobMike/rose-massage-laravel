@extends('layouts.user')

@section('title', 'Healing Rituals | Rose')

@section('content')
<div class="container pb-5">

    <header class="py-5 text-center reveal">
        <div class="mx-auto" style="max-width: 800px;">
            <span class="text-rose text-uppercase small-label d-block mb-3">
                The Art of Stillness
            </span>
            <h1 class="display-4 mb-3">Our <span class="fst-italic text-serif">Healing Rituals</span></h1>
            <p class="text-muted body-text px-lg-5">
                A curated collection of sensory journeys designed to harmonize the spirit. 
                Every treatment is a bespoke path to restoration.
            </p>
        </div>
    </header>

    <section class="mb-5 reveal">
        <div class="card-wellness p-4">
            <form action="{{ route('services.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="filter-label">Find Your Ritual</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control zen-input border-start-0" placeholder="Search rituals..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <label class="filter-label">Duration</label>
                    <select name="duration" class="form-select zen-input">
                        <option value="">Any Time</option>
                        <option value="short" {{ request('duration') == 'short' ? 'selected' : '' }}>30-45m</option>
                        <option value="60" {{ request('duration') == '60' ? 'selected' : '' }}>60m</option>
                        <option value="90" {{ request('duration') == '90' ? 'selected' : '' }}>90m+</option>
                    </select>
                </div>

                <div class="col-6 col-md-3">
                    <label class="filter-label">Investment</label>
                    <select name="price" class="form-select zen-input">
                        <option value="">All Tiers</option>
                        <option value="low" {{ request('price') == 'low' ? 'selected' : '' }}>Essential</option>
                        <option value="mid" {{ request('price') == 'mid' ? 'selected' : '' }}>Signature</option>
                        <option value="high" {{ request('price') == 'high' ? 'selected' : '' }}>Premium</option>
                    </select>
                </div>

                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-rose flex-grow-1 py-2">Filter</button>
                    @if(request()->anyFilled(['search','duration','price']))
                        <a href="{{ route('services.index') }}" class="btn btn-outline-secondary rounded-pill px-3 d-flex align-items-center" title="Reset filters">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </section>

    <section class="row g-4" aria-label="Available rituals">
    @forelse($services as $service)
        <div class="col-12 col-md-6 col-lg-4 reveal">
            <article class="ritual-card h-100">
                <div class="card-image-box">
                    @if($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}" class="ritual-img" alt="{{ $service->name }}">
                    @else
                        <div class="ritual-placeholder"><i class="bi bi-flower1"></i></div>
                    @endif
                    <div class="price-pill">₱{{ number_format($service->price, 0) }}</div>
                </div>

                <div class="card-content">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h2 class="h4 fw-bold text-dark mb-0">{{ $service->name }}</h2>
                        <span class="badge-duration">{{ $service->duration_minutes }}m</span>
                    </div>
                    
                    <p class="ritual-description mb-4">
                        {{ Str::limit($service->description, 140) }}
                    </p>

                    <a href="{{ route('bookings.create',['service'=>$service->id]) }}" class="btn-journey mt-auto">
                        Begin Journey <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </article>
        </div>
    @empty
        <div class="col-12 text-center py-5 reveal">
            <div class="py-5">
                <i class="bi bi-wind display-1 text-light mb-4 d-block"></i>
                <h3 class="fw-bold">A Moment of Silence</h3>
                <p class="body-text text-muted">No rituals match your current search. Try resetting your filters.</p>
                <a href="{{ route('services.index') }}" class="btn btn-outline-dark mt-3">View All Rituals</a>
            </div>
        </div>
    @endforelse
    </section>

    @if($services->hasPages())
    <div class="mt-5 d-flex justify-content-center reveal">
        {{ $services->appends(request()->input())->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection

