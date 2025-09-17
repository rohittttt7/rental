@extends('layouts.app')

@section('title', 'EquipZone - Buy & Rent Construction, Agriculture & Industrial Machinery')
@section('description', 'Find the best deals on construction, agriculture, and industrial machinery. Buy or rent equipment from trusted sellers across the country.')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Find the Perfect Machinery for Your Needs</h1>
                <p class="lead mb-4">Buy or rent construction, agriculture, and industrial equipment from trusted sellers. Get the tools you need to get the job done.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('machinery.browse') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-search me-2"></i>Browse Machinery
                    </a>
                    <a href="#" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-plus me-2"></i>List Your Equipment
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image-placeholder">
                    <!-- Hero image would go here -->
                    <div style="height: 400px; background: rgba(255,255,255,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-tractor" style="font-size: 8rem; color: rgba(255,255,255,0.3);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="stat-item">
                    <div class="stat-number">{{ number_format($stats['total_machinery']) }}</div>
                    <p class="text-muted">Machinery Available</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <div class="stat-number">{{ number_format($stats['total_categories']) }}</div>
                    <p class="text-muted">Categories</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <div class="stat-number">{{ number_format($stats['total_sellers']) }}</div>
                    <p class="text-muted">Trusted Sellers</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <div class="stat-number">{{ number_format($stats['total_rentals']) }}</div>
                    <p class="text-muted">Successful Rentals</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Browse by Category</h2>
            <p class="text-muted">Find machinery for every industry and project</p>
        </div>
        
        <div class="row g-4">
            @forelse($categories as $category)
            <div class="col-lg-4 col-md-6">
                <div class="category-card h-100">
                    <div class="category-icon">
                        <i class="fas fa-{{ $category->icon ?? 'cog' }}"></i>
                    </div>
                    <h5 class="fw-bold">{{ $category->name }}</h5>
                    <p class="text-muted">{{ $category->description }}</p>
                    <p class="small text-primary">{{ $category->active_machinery_count }} items available</p>
                    <a href="{{ route('machinery.category', $category) }}" class="btn btn-outline-primary">
                        View Category
                    </a>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p class="text-muted">No categories available at the moment.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Featured Machinery Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Featured Machinery</h2>
            <p class="text-muted">Latest additions to our marketplace</p>
        </div>
        
        <div class="row g-4">
            @forelse($featuredMachinery as $machinery)
            <div class="col-lg-3 col-md-6">
                <div class="card machinery-card h-100">
                    <div class="position-relative">
                        @if($machinery->images && count($machinery->images) > 0)
                            <img src="{{ $machinery->images[0] }}" class="card-img-top" alt="{{ $machinery->name }}">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center">
                                <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        
                        <!-- Availability badges -->
                        <div class="position-absolute top-0 start-0 m-2">
                            @if($machinery->availability_type === 'sale' || $machinery->availability_type === 'both')
                                <span class="badge bg-success">For Sale</span>
                            @endif
                            @if($machinery->availability_type === 'rent' || $machinery->availability_type === 'both')
                                <span class="badge bg-primary">For Rent</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <h6 class="card-title">{{ $machinery->name }}</h6>
                        <p class="text-muted small">{{ $machinery->category->name }} • {{ $machinery->condition }}</p>
                        
                        <div class="mb-2">
                            @if($machinery->availability_type === 'sale' || $machinery->availability_type === 'both')
                                <div class="fw-bold text-success">${{ number_format($machinery->price, 2) }}</div>
                            @endif
                            @if($machinery->availability_type === 'rent' || $machinery->availability_type === 'both')
                                <div class="small text-primary">${{ number_format($machinery->daily_rate, 2) }}/day</div>
                            @endif
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center small text-muted">
                            <span><i class="fas fa-map-marker-alt"></i> {{ $machinery->location }}</span>
                            <span><i class="fas fa-eye"></i> {{ $machinery->view_count }}</span>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent">
                        <a href="{{ route('machinery.show', $machinery) }}" class="btn btn-outline-primary btn-sm w-100">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p class="text-muted">No machinery available at the moment.</p>
            </div>
            @endforelse
        </div>
        
        @if($featuredMachinery->count() > 0)
        <div class="text-center mt-4">
            <a href="{{ route('machinery.browse') }}" class="btn btn-primary">
                View All Machinery <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        @endif
    </div>
</section>

<!-- How It Works Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">How EquipZone Works</h2>
            <p class="text-muted">Simple steps to get the machinery you need</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <div class="mb-3">
                    <i class="fas fa-search text-primary" style="font-size: 3rem;"></i>
                </div>
                <h5>1. Search & Browse</h5>
                <p class="text-muted">Find the perfect machinery from our extensive catalog of construction, agriculture, and industrial equipment.</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="mb-3">
                    <i class="fas fa-calendar-check text-primary" style="font-size: 3rem;"></i>
                </div>
                <h5>2. Book or Buy</h5>
                <p class="text-muted">Choose to purchase or rent the equipment. Select your dates for rentals and complete the booking process.</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="mb-3">
                    <i class="fas fa-truck text-primary" style="font-size: 3rem;"></i>
                </div>
                <h5>3. Get Delivered</h5>
                <p class="text-muted">Arrange pickup or delivery of your machinery. Start using it for your project right away.</p>
            </div>
        </div>
    </div>
</section>
@endsection