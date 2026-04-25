@extends('layouts.user')

@section('title', 'Rose Sanctuary | A Journey to Peace')

@section('content')

<div class="position-relative overflow-hidden" style="height: 95vh; background-color: var(--bg);">
    
    <div class="position-absolute top-0 start-0 w-100 h-100">
        <div class="position-absolute w-100 h-100 z-1" 
             style="background: linear-gradient(to bottom, rgba(253,251,250,0.4) 0%, rgba(253,251,250,0.1) 50%, rgba(253,251,250,0.8) 100%);">
        </div>
        
        <div class="w-100 h-100" 
             style="background: url('https://images.unsplash.com/photo-1540555700478-4be289fbecee?auto=format&fit=crop&q=80&w=1600') center/cover no-repeat; opacity: 0.8;">
        </div>
    </div>

    <div class="container h-100 position-relative z-2">
        <div class="row h-100 align-items-center justify-content-center">
            <div class="col-lg-8 text-center reveal">
                
                <span class="d-block text-uppercase small tracking-widest mb-4" style="color: var(--accent); font-weight: 500;">
                    Siquijor’s Premier Healing Space
                </span>

                <h1 class="display-2 mb-4" style="line-height: 1.1;">
                    Find Your <span class="fst-italic text-zen-serif">Inner Stillness</span>
                </h1>

                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <p class="lead text-muted mb-5 fw-light" style="font-size: 1.15rem; letter-spacing: 0.02em;">
                            Transcend the everyday. Immerse yourself in a sanctuary where 
                            traditional Filipino healing meets modern clinical wellness. 
                            Your journey to restoration begins with a single breath.
                        </p>
                    </div>
                </div>

                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center align-items-center">
                    <a href="{{ route('services.index') }}" class="btn btn-rose shadow-sm">
                        Explore Rituals
                    </a>
                    <a href="#about" class="btn btn-link text-decoration-none text-muted small tracking-widest text-uppercase">
                        Our Philosophy <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

            </div>
        </div>
    </div>

    <div class="position-absolute bottom-0 start-50 translate-middle-x mb-5 z-2 d-none d-md-block">
        <div class="scroll-line"></div>
    </div>
</div>

<section class="py-5" id="about" style="background-color: var(--surface);">
    <div class="container py-5 text-center">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <i class="bi bi-stars fs-3 mb-4" style="color: var(--accent);"></i>
                <h2 class="h4 text-uppercase tracking-widest mb-4">Mindful Restoration</h2>
                <p class="text-muted">
                    At Rose, we believe that massage is not just a luxury, but a necessity for the soul. 
                    Our therapists are masters of touch, trained to release tension and invite peace 
                    back into your physical being.
                </p>
            </div>
        </div>
    </div>
</section>

@endsection

@section('page-styles')
<style>
    /* Hero Content Animation */
    .reveal {
        animation: fadeUp 1.4s cubic-bezier(0.2, 0, 0.2, 1) forwards;
    }

    /* Elegant Vertical Scroll Line */
    .scroll-line {
        width: 1px;
        height: 60px;
        background: linear-gradient(to bottom, var(--accent), transparent);
        margin: 0 auto;
        position: relative;
        overflow: hidden;
    }

    .scroll-line::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: var(--bg);
        animation: scrollMove 2s infinite;
    }

    @keyframes scrollMove {
        0% { transform: translateY(-100%); }
        100% { transform: translateY(100%); }
    }

    /* Fluid Typography for Hero Title */
    @media (max-width: 768px) {
        h1.display-2 {
            font-size: 2.8rem;
        }
    }
</style>
@endsection