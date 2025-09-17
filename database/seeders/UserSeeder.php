<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@equipzone.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+1234567890',
            'address' => '123 Admin St, Admin City, AC 12345',
            'kyc_status' => 'verified',
            'is_active' => true,
        ]);

        // Create some sellers
        $sellers = [
            [
                'name' => 'Heavy Equipment Co.',
                'email' => 'contact@heavyequip.com',
                'role' => 'seller',
                'company_name' => 'Heavy Equipment Co.',
                'company_address' => '456 Industrial Blvd, Industrial City, IC 67890',
                'phone' => '+1234567891',
                'kyc_status' => 'verified',
            ],
            [
                'name' => 'Farm Machinery Solutions',
                'email' => 'info@farmmachinery.com',
                'role' => 'seller',
                'company_name' => 'Farm Machinery Solutions',
                'company_address' => '789 Farm Road, Farm Town, FT 11111',
                'phone' => '+1234567892',
                'kyc_status' => 'verified',
            ],
            [
                'name' => 'Construction Rentals Inc',
                'email' => 'sales@constructionrentals.com',
                'role' => 'seller',
                'company_name' => 'Construction Rentals Inc',
                'company_address' => '321 Construction Ave, Build City, BC 22222',
                'phone' => '+1234567893',
                'kyc_status' => 'verified',
            ],
        ];

        foreach ($sellers as $seller) {
            User::create(array_merge($seller, [
                'password' => Hash::make('password'),
                'is_active' => true,
            ]));
        }

        // Create some customers
        $customers = [
            [
                'name' => 'John Smith',
                'email' => 'john@example.com',
                'role' => 'customer',
                'phone' => '+1234567894',
                'address' => '111 Customer St, Customer City, CC 33333',
            ],
            [
                'name' => 'Jane Doe',
                'email' => 'jane@example.com',
                'role' => 'customer',
                'phone' => '+1234567895',
                'address' => '222 Buyer Ave, Buyer Town, BT 44444',
            ],
        ];

        foreach ($customers as $customer) {
            User::create(array_merge($customer, [
                'password' => Hash::make('password'),
                'kyc_status' => 'pending',
                'is_active' => true,
            ]));
        }
    }
}