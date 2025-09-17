# EquipZone - Machinery Selling and Rental Platform

EquipZone is a specialized e-commerce platform focused on machinery selling and rental. Built with PHP and Laravel framework, it provides comprehensive solutions for both selling workflows and rental workflows.

## Features

### 🔹 User Management
- User Registration & Login (Customers, Sellers, Admins)
- Profile Management with KYC verification
- Role-based access control

### 🔹 Product Management
- Machinery listings with categorization
- Product details with images and specifications
- Rental settings with flexible pricing

### 🔹 Shopping & Rental System
- Purchase cart functionality
- Rental booking with date selection
- Dynamic pricing calculation
- Availability checking

### 🔹 Payment & Orders
- Multiple payment methods
- Order tracking and management
- Rental status management

### 🔹 Communication & Reviews
- Review and rating system
- Buyer-seller communication
- Admin panel for management

## Requirements

- PHP 8.3+
- Laravel 12.x
- MySQL/PostgreSQL
- Composer

## Installation

1. Clone the repository
2. Install dependencies: `composer install`
3. Copy `.env.example` to `.env`
4. Generate application key: `php artisan key:generate`
5. Run migrations: `php artisan migrate`
6. Start the server: `php artisan serve`

## Project Structure

- `/app/Models/` - Eloquent models for machinery, users, orders, rentals
- `/app/Http/Controllers/` - Controllers for different functionalities
- `/database/migrations/` - Database schema migrations
- `/routes/` - Web and API routes
- `/resources/views/` - Blade templates for UI

## License

Open source project for machinery equipment rental and selling platform.
