@extends('layouts.app')

@section('title', 'Browse Machinery - EquipZone')
@section('description', 'Browse and filter our extensive collection of machinery for sale and rent.')

@section('content')
<!-- Page Header -->
<div class="bg-light py-4">
    <div class="container">
        <h1 class="h3 mb-0">Browse Machinery</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Browse Machinery</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-4">
    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filters</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('machinery.browse') }}">
                        <!-- Search -->
                        <div class="mb-3">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" name="search" id="search" 
                                   value="{{ request('search') }}" placeholder="Enter keywords...">
                        </div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" name="category" id="category">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Availability Type -->
                        <div class="mb-3">
                            <label for="type" class="form-label">Availability</label>
                            <select class="form-select" name="type" id="type">
                                <option value="">Sale & Rental</option>
                                <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>For Sale Only</option>
                                <option value="rent" {{ request('type') == 'rent' ? 'selected' : '' }}>For Rent Only</option>
                            </select>
                        </div>

                        <!-- Condition -->
                        <div class="mb-3">
                            <label for="condition" class="form-label">Condition</label>
                            <select class="form-select" name="condition" id="condition">
                                <option value="">Any Condition</option>
                                <option value="new" {{ request('condition') == 'new' ? 'selected' : '' }}>New</option>
                                <option value="used" {{ request('condition') == 'used' ? 'selected' : '' }}>Used</option>
                                <option value="refurbished" {{ request('condition') == 'refurbished' ? 'selected' : '' }}>Refurbished</option>
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-3">
                            <label class="form-label">Price Range</label>
                            <div class="row g-2">
                                <div class="col">
                                    <input type="number" class="form-control" name="min_price" 
                                           value="{{ request('min_price') }}" placeholder="Min">
                                </div>
                                <div class="col">
                                    <input type="number" class="form-control" name="max_price" 
                                           value="{{ request('max_price') }}" placeholder="Max">
                                </div>
                            </div>
                        </div>

                        <!-- Brand -->
                        <div class="mb-3">
                            <label for="brand" class="form-label">Brand</label>
                            <input type="text" class="form-control" name="brand" id="brand" 
                                   value="{{ request('brand') }}" placeholder="Brand name">
                        </div>

                        <!-- Location -->
                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" name="location" id="location" 
                                   value="{{ request('location') }}" placeholder="City or state">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                            <a href="{{ route('machinery.browse') }}" class="btn btn-outline-secondary">Clear All</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Results -->
        <div class="col-lg-9">
            <!-- Results Header -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="mb-0">{{ $machinery->total() }} machinery found</h6>
                </div>
                <div class="d-flex gap-2">
                    <!-- Sort Options -->
                    <form method="GET" action="{{ route('machinery.browse') }}" class="d-flex gap-2">
                        @foreach(request()->except('sort') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        
                        <select name="sort" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                        </select>
                    </form>

                    <!-- View Toggle -->
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary btn-sm active" data-view="grid">
                            <i class="fas fa-th"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-view="list">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Machinery Grid -->
            <div class="row g-4" id="machinery-grid">
                @forelse($machinery as $machine)
                <div class="col-lg-4 col-md-6">
                    <div class="card machinery-card h-100">
                        <div class="position-relative">
                            @if($machine->images && count($machine->images) > 0)
                                <img src="{{ $machine->images[0] }}" class="card-img-top" alt="{{ $machine->name }}">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                            
                            <!-- Availability badges -->
                            <div class="position-absolute top-0 start-0 m-2">
                                @if($machine->availability_type === 'sale' || $machine->availability_type === 'both')
                                    <span class="badge bg-success">For Sale</span>
                                @endif
                                @if($machine->availability_type === 'rent' || $machine->availability_type === 'both')
                                    <span class="badge bg-primary">For Rent</span>
                                @endif
                            </div>

                            <!-- Condition badge -->
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-secondary">{{ ucfirst($machine->condition) }}</span>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <h6 class="card-title">{{ $machine->name }}</h6>
                            <p class="text-muted small mb-1">{{ $machine->category->name }}</p>
                            <p class="text-muted small mb-2">{{ $machine->brand }} {{ $machine->model }}</p>
                            
                            <div class="mb-2">
                                @if($machine->availability_type === 'sale' || $machine->availability_type === 'both')
                                    <div class="fw-bold text-success">${{ number_format($machine->price, 2) }}</div>
                                @endif
                                @if($machine->availability_type === 'rent' || $machine->availability_type === 'both')
                                    <div class="small text-primary">${{ number_format($machine->daily_rate, 2) }}/day</div>
                                @endif
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center small text-muted">
                                <span><i class="fas fa-map-marker-alt"></i> {{ $machine->location }}</span>
                                <span><i class="fas fa-eye"></i> {{ $machine->view_count }}</span>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-transparent">
                            <div class="d-grid">
                                <a href="{{ route('machinery.show', $machine) }}" class="btn btn-outline-primary btn-sm">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-search text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">No machinery found</h4>
                        <p class="text-muted">Try adjusting your filters or search terms</p>
                        <a href="{{ route('machinery.browse') }}" class="btn btn-primary">Clear Filters</a>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($machinery->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $machinery->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// View toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const viewButtons = document.querySelectorAll('[data-view]');
    const machineryGrid = document.getElementById('machinery-grid');
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const view = this.dataset.view;
            
            // Update button states
            viewButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Update grid layout
            if (view === 'list') {
                machineryGrid.className = 'row g-3';
                machineryGrid.querySelectorAll('.col-lg-4').forEach(col => {
                    col.className = 'col-12';
                });
            } else {
                machineryGrid.className = 'row g-4';
                machineryGrid.querySelectorAll('.col-12').forEach(col => {
                    col.className = 'col-lg-4 col-md-6';
                });
            }
        });
    });
});
</script>
@endpush