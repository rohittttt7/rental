<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EquipZone - Machinery Rental & Sales Platform')</title>
    <meta name="description" content="@yield('description', 'EquipZone is your trusted platform for buying and renting construction, agriculture, and industrial machinery.')">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #f97316;
            --success-color: #059669;
            --warning-color: #d97706;
            --danger-color: #dc2626;
        }
        
        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color) !important;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }
        
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        .bg-primary {
            background-color: var(--primary-color) !important;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
            color: white;
            padding: 5rem 0;
        }
        
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .machinery-card .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        
        .category-card {
            text-align: center;
            padding: 2rem 1rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .category-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .stats-section {
            background-color: #f8fafc;
            padding: 4rem 0;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .footer {
            background-color: #1f2937;
            color: white;
            padding: 3rem 0 1rem;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-cog me-2"></i>EquipZone
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('machinery.browse') }}">Browse Machinery</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-bs-toggle="dropdown">
                            Categories
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Construction</a></li>
                            <li><a class="dropdown-item" href="#">Agriculture</a></li>
                            <li><a class="dropdown-item" href="#">Industrial</a></li>
                            <li><a class="dropdown-item" href="#">Heavy Duty</a></li>
                        </ul>
                    </li>
                </ul>
                
                <!-- Search Form -->
                <form class="d-flex me-3" action="{{ route('machinery.search') }}" method="GET">
                    <input class="form-control me-2" type="search" name="q" placeholder="Search machinery..." value="{{ request('q') }}">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                
                <ul class="navbar-nav">
                    <!-- Authentication links would go here -->
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-shopping-cart"></i> Cart</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary ms-2" href="#">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <h5><i class="fas fa-cog me-2"></i>EquipZone</h5>
                    <p>Your trusted platform for buying and renting construction, agriculture, and industrial machinery.</p>
                </div>
                <div class="col-md-3">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('machinery.browse') }}" class="text-light text-decoration-none">Browse Machinery</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Categories</a></li>
                        <li><a href="#" class="text-light text-decoration-none">About Us</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>For Sellers</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light text-decoration-none">List Your Machinery</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Seller Dashboard</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Pricing</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Support</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light text-decoration-none">Help Center</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Safety Tips</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Terms of Service</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p>&copy; 2024 EquipZone. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>