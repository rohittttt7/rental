# EquipZone Project Structure and Implementation Guide

## Overview
EquipZone is a comprehensive e-commerce platform for machinery selling and rental, built with PHP and Laravel framework. The platform supports both purchase and rental workflows for construction, agriculture, and industrial equipment.

## Project Structure

### Core Models
- **User**: Multi-role system (customer, seller, admin) with KYC verification
- **Category**: Machinery categorization system
- **Machinery**: Complete equipment listing with sale/rental capabilities
- **Order**: Purchase and rental order management
- **Rental**: Dedicated rental booking system
- **Review**: Customer feedback and rating system
- **CartItem**: Shopping cart functionality
- **Message**: Buyer-seller communication system

### Controllers
- **HomeController**: Homepage and browsing functionality
- **MachineryController**: Equipment details and search
- **AuthController**: User authentication system
- **CartController**: Shopping cart management
- **CustomerDashboardController**: Customer-specific features
- **SellerDashboardController**: Seller management interface

### Key Features Implemented

#### 1. User Management System
- Multi-role authentication (Customer, Seller, Admin)
- Registration with role-specific fields
- KYC verification for sellers
- Profile management
- Company information for sellers

#### 2. Machinery Management
- Comprehensive listing system
- Category-based organization
- Dual availability (sale/rent)
- Detailed specifications
- Image support (structure ready)
- Advanced search and filtering
- Location-based discovery

#### 3. Shopping and Rental System
- Shopping cart functionality
- Dual purchase/rental booking
- Dynamic pricing calculation
- Availability checking
- Date-based rental booking
- Real-time availability validation

#### 4. Review and Rating System
- Customer feedback collection
- Verified purchase/rental reviews
- Star rating system
- Review management

#### 5. Communication System
- Buyer-seller messaging
- Inquiry system for machinery
- Contact management

### Database Schema

#### Users Table
```sql
- id, name, email, password
- role (customer, seller, admin)
- phone, address
- company_name, company_address (for sellers)
- kyc_status, kyc_documents
- is_active, timestamps
```

#### Machinery Table
```sql
- id, seller_id, category_id
- name, slug, description
- price, daily_rate, weekly_rate, monthly_rate
- condition, availability_type
- brand, model, year, fuel_type
- specifications (JSON), images (JSON)
- location, latitude, longitude
- view_count, status, timestamps
```

#### Orders Table
```sql
- id, order_number, buyer_id, seller_id, machinery_id
- type (purchase/rental), amount, tax_amount, total_amount
- status, payment_status, payment_method
- shipping_address, notes
- shipped_at, delivered_at, timestamps
```

#### Rentals Table
```sql
- id, rental_number, renter_id, machinery_id, order_id
- start_date, end_date, rental_days
- daily_rate, total_amount, security_deposit
- status, pickup/delivery addresses and schedules
- extension support, timestamps
```

### Frontend Implementation

#### Technology Stack
- **Bootstrap 5**: Responsive UI framework
- **Font Awesome**: Icon library
- **JavaScript**: Interactive features
- **Blade Templates**: Laravel templating engine

#### Key Pages
- Homepage with featured machinery and categories
- Advanced machinery browse page with filters
- Detailed machinery view with booking system
- Authentication pages (login/register)
- User dashboards (customer/seller specific)
- Shopping cart interface

#### Interactive Features
- AJAX cart updates
- Real-time availability checking
- Dynamic rental calculations
- Responsive design
- Toast notifications
- Modal-based interactions

### Security and Authorization

#### Authentication
- Laravel's built-in authentication
- Role-based access control
- Session management
- CSRF protection

#### Authorization Policies
- CartItemPolicy: User cart access control
- MachineryPolicy: Seller equipment permissions
- Role-based route protection

### API Endpoints

#### Public Routes
- GET / (Homepage)
- GET /browse (Machinery browsing)
- GET /search (Machinery search)
- GET /machinery/{machinery} (Equipment details)
- GET /category/{category} (Category pages)

#### Authentication Routes
- GET|POST /login
- GET|POST /register
- POST /logout

#### Protected Routes
- Cart management (/cart/*)
- User dashboards (/customer/*, /seller/*)
- AJAX endpoints for interactivity

### Installation and Setup

#### Requirements
- PHP 8.3+
- Laravel 12.x
- MySQL/PostgreSQL
- Composer

#### Installation Steps
1. Clone repository
2. Install dependencies: `composer install`
3. Copy environment file: `cp .env.example .env`
4. Generate application key: `php artisan key:generate`
5. Configure database in .env
6. Run migrations: `php artisan migrate`
7. Seed database: `php artisan db:seed`
8. Start server: `php artisan serve`

### Sample Data
The project includes comprehensive seeders that create:
- Admin, seller, and customer users
- Equipment categories (Construction, Agriculture, Industrial, etc.)
- Sample machinery listings with realistic data
- Proper relationships between all entities

### Future Enhancements
- Payment gateway integration
- File upload system for machinery images
- Email notification system
- Admin panel for platform management
- Advanced analytics and reporting
- Mobile application
- Real-time messaging system

### Development Notes
- All models include proper relationships
- Database queries are optimized with eager loading
- Form validation implemented throughout
- Responsive design ensures mobile compatibility
- Code follows Laravel best practices
- Security measures implemented at all levels

This implementation provides a solid foundation for a machinery rental and sales platform that can be extended with additional features as needed.