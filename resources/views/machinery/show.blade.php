@extends('layouts.app')

@section('title', $machinery->name . ' - EquipZone')
@section('description', 'View details for ' . $machinery->name . ' - ' . substr($machinery->description, 0, 150))

@section('content')
<!-- Breadcrumb -->
<div class="bg-light py-3">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('machinery.browse') }}">Browse</a></li>
                <li class="breadcrumb-item"><a href="{{ route('machinery.category', $machinery->category) }}">{{ $machinery->category->name }}</a></li>
                <li class="breadcrumb-item active">{{ $machinery->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-4">
    <div class="row">
        <!-- Images Column -->
        <div class="col-lg-6">
            <div class="machinery-images">
                @if($machinery->images && count($machinery->images) > 0)
                    <div id="machineryCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner rounded">
                            @foreach($machinery->images as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ $image }}" class="d-block w-100" alt="{{ $machinery->name }}" style="height: 400px; object-fit: cover;">
                            </div>
                            @endforeach
                        </div>
                        @if(count($machinery->images) > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#machineryCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#machineryCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                        @endif
                    </div>
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 400px;">
                        <i class="fas fa-image text-muted" style="font-size: 5rem;"></i>
                    </div>
                @endif
            </div>
        </div>

        <!-- Details Column -->
        <div class="col-lg-6">
            <div class="machinery-details">
                <!-- Title and basic info -->
                <div class="mb-4">
                    <h1 class="h3 mb-2">{{ $machinery->name }}</h1>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <span class="badge bg-primary">{{ $machinery->category->name }}</span>
                        <span class="badge bg-secondary">{{ ucfirst($machinery->condition) }}</span>
                        @if($machinery->availability_type === 'sale' || $machinery->availability_type === 'both')
                            <span class="badge bg-success">For Sale</span>
                        @endif
                        @if($machinery->availability_type === 'rent' || $machinery->availability_type === 'both')
                            <span class="badge bg-info">For Rent</span>
                        @endif
                    </div>
                    <p class="text-muted mb-1">
                        <i class="fas fa-map-marker-alt"></i> {{ $machinery->location }}
                        <span class="ms-3"><i class="fas fa-eye"></i> {{ $machinery->view_count }} views</span>
                    </p>
                    @if($averageRating > 0)
                    <div class="mb-2">
                        <span class="text-warning">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $averageRating ? '' : '-o' }}"></i>
                            @endfor
                        </span>
                        <span class="text-muted">({{ $reviewCount }} reviews)</span>
                    </div>
                    @endif
                </div>

                <!-- Pricing -->
                <div class="mb-4">
                    @if($machinery->availability_type === 'sale' || $machinery->availability_type === 'both')
                        <div class="pricing-sale mb-2">
                            <h4 class="text-success mb-0">${{ number_format($machinery->price, 2) }}</h4>
                            <small class="text-muted">Purchase Price</small>
                        </div>
                    @endif
                    
                    @if($machinery->availability_type === 'rent' || $machinery->availability_type === 'both')
                        <div class="pricing-rental">
                            <div class="row g-2">
                                @if($machinery->daily_rate)
                                <div class="col-4">
                                    <div class="text-center p-2 border rounded">
                                        <div class="fw-bold text-primary">${{ number_format($machinery->daily_rate, 2) }}</div>
                                        <small class="text-muted">per day</small>
                                    </div>
                                </div>
                                @endif
                                @if($machinery->weekly_rate)
                                <div class="col-4">
                                    <div class="text-center p-2 border rounded">
                                        <div class="fw-bold text-primary">${{ number_format($machinery->weekly_rate, 2) }}</div>
                                        <small class="text-muted">per week</small>
                                    </div>
                                </div>
                                @endif
                                @if($machinery->monthly_rate)
                                <div class="col-4">
                                    <div class="text-center p-2 border rounded">
                                        <div class="fw-bold text-primary">${{ number_format($machinery->monthly_rate, 2) }}</div>
                                        <small class="text-muted">per month</small>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="mb-4">
                    @auth
                        @if($machinery->availability_type === 'sale' || $machinery->availability_type === 'both')
                            <button class="btn btn-success btn-lg me-2" onclick="addToCart({{ $machinery->id }}, 'purchase')">
                                <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                            </button>
                        @endif
                        
                        @if($machinery->availability_type === 'rent' || $machinery->availability_type === 'both')
                            <button class="btn btn-primary btn-lg me-2" data-bs-toggle="modal" data-bs-target="#rentalModal">
                                <i class="fas fa-calendar-check me-2"></i>Book Rental
                            </button>
                        @endif
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Please <a href="{{ route('login') }}" class="alert-link">login</a> to purchase or rent this machinery.
                        </div>
                    @endauth
                    
                    <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#contactModal">
                        <i class="fas fa-envelope me-2"></i>Contact Seller
                    </button>
                </div>

                <!-- Seller Info -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="card-title">Seller Information</h6>
                        <p class="card-text">
                            <strong>{{ $machinery->seller->name }}</strong><br>
                            @if($machinery->seller->company_name)
                                <small class="text-muted">{{ $machinery->seller->company_name }}</small><br>
                            @endif
                            <small class="text-muted">
                                <i class="fas fa-phone"></i> {{ $machinery->seller->phone }}
                            </small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Specifications and Description -->
    <div class="row mt-5">
        <div class="col-12">
            <ul class="nav nav-tabs" id="machineryTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button">
                        Description
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button">
                        Specifications
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button">
                        Reviews ({{ $reviewCount }})
                    </button>
                </li>
            </ul>
            
            <div class="tab-content mt-3" id="machineryTabsContent">
                <div class="tab-pane fade show active" id="description" role="tabpanel">
                    <div class="p-3">
                        <p>{{ $machinery->description }}</p>
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h6>Basic Information</h6>
                                <table class="table table-sm">
                                    <tr><td><strong>Brand:</strong></td><td>{{ $machinery->brand }}</td></tr>
                                    <tr><td><strong>Model:</strong></td><td>{{ $machinery->model }}</td></tr>
                                    <tr><td><strong>Year:</strong></td><td>{{ $machinery->year }}</td></tr>
                                    <tr><td><strong>Condition:</strong></td><td>{{ ucfirst($machinery->condition) }}</td></tr>
                                    <tr><td><strong>Fuel Type:</strong></td><td>{{ ucfirst($machinery->fuel_type) }}</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="tab-pane fade" id="specifications" role="tabpanel">
                    <div class="p-3">
                        @if($machinery->specifications && count($machinery->specifications) > 0)
                            <div class="row">
                                @foreach(array_chunk($machinery->specifications, ceil(count($machinery->specifications) / 2), true) as $chunk)
                                <div class="col-md-6">
                                    <table class="table table-sm">
                                        @foreach($chunk as $key => $value)
                                        <tr>
                                            <td><strong>{{ $key }}:</strong></td>
                                            <td>{{ $value }}</td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No specifications available.</p>
                        @endif
                    </div>
                </div>
                
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <div class="p-3">
                        @if($machinery->reviews->count() > 0)
                            @foreach($machinery->reviews as $review)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $review->user->name }}</h6>
                                        <div class="text-warning mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                            @endfor
                                        </div>
                                        @if($review->review)
                                            <p class="mb-0">{{ $review->review }}</p>
                                        @endif
                                    </div>
                                    <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <p class="text-muted">No reviews yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Machinery -->
    @if($relatedMachinery->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <h4 class="mb-4">Related Machinery</h4>
            <div class="row g-4">
                @foreach($relatedMachinery as $related)
                <div class="col-lg-3 col-md-6">
                    <div class="card machinery-card h-100">
                        <div class="position-relative">
                            @if($related->images && count($related->images) > 0)
                                <img src="{{ $related->images[0] }}" class="card-img-top" alt="{{ $related->name }}">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image text-muted" style="font-size: 2rem;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">{{ $related->name }}</h6>
                            <p class="text-muted small">{{ $related->brand }} {{ $related->model }}</p>
                            <div class="fw-bold text-success">${{ number_format($related->price, 2) }}</div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="{{ route('machinery.show', $related) }}" class="btn btn-outline-primary btn-sm w-100">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

@auth
<!-- Rental Modal -->
@if($machinery->availability_type === 'rent' || $machinery->availability_type === 'both')
<div class="modal fade" id="rentalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Book Rental</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="rentalForm">
                    <div class="mb-3">
                        <label for="rental_start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="rental_start_date" name="rental_start_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="rental_end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="rental_end_date" name="rental_end_date" required>
                    </div>
                    <div class="alert alert-info d-none" id="rental-calculation">
                        <!-- Rental calculation will be shown here -->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="addRentalToCart">Add to Cart</button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Contact Modal -->
<div class="modal fade" id="contactModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Contact Seller</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="contactForm">
                    <div class="mb-3">
                        <label for="contact_subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="contact_subject" name="subject" 
                               value="Inquiry about {{ $machinery->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="contact_message" class="form-label">Message</label>
                        <textarea class="form-control" id="contact_message" name="message" rows="4" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="sendMessage">Send Message</button>
            </div>
        </div>
    </div>
</div>
@endauth
@endsection

@push('scripts')
<script>
@auth
// Rental date validation and calculation
document.getElementById('rental_start_date').addEventListener('change', calculateRental);
document.getElementById('rental_end_date').addEventListener('change', calculateRental);

function calculateRental() {
    const startDate = document.getElementById('rental_start_date').value;
    const endDate = document.getElementById('rental_end_date').value;
    
    if (startDate && endDate) {
        fetch('{{ route("machinery.check-availability", $machinery) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                start_date: startDate,
                end_date: endDate
            })
        })
        .then(response => response.json())
        .then(data => {
            const calculationDiv = document.getElementById('rental-calculation');
            calculationDiv.classList.remove('d-none');
            
            if (data.available) {
                calculationDiv.className = 'alert alert-success';
                calculationDiv.innerHTML = `
                    <strong>Available!</strong><br>
                    Duration: ${data.days} days<br>
                    Daily Rate: $${data.daily_rate}<br>
                    Total Cost: $${data.total_cost}
                `;
                document.getElementById('addRentalToCart').disabled = false;
            } else {
                calculationDiv.className = 'alert alert-danger';
                calculationDiv.innerHTML = `<strong>Not Available:</strong> ${data.message}`;
                document.getElementById('addRentalToCart').disabled = true;
            }
        });
    }
}

// Add rental to cart
document.getElementById('addRentalToCart').addEventListener('click', function() {
    const startDate = document.getElementById('rental_start_date').value;
    const endDate = document.getElementById('rental_end_date').value;
    
    addToCart({{ $machinery->id }}, 'rental', {
        rental_start_date: startDate,
        rental_end_date: endDate
    });
    
    // Close modal
    bootstrap.Modal.getInstance(document.getElementById('rentalModal')).hide();
});

// Set minimum date to today
document.getElementById('rental_start_date').min = new Date().toISOString().split('T')[0];
document.getElementById('rental_end_date').min = new Date().toISOString().split('T')[0];
@endauth
</script>
@endpush