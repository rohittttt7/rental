<?php

namespace Database\Seeders;

use App\Models\Machinery;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MachinerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sellers = User::where('role', 'seller')->get();
        $categories = Category::all();

        $machineryData = [
            // Construction Equipment
            [
                'name' => 'CAT 320D Excavator',
                'description' => 'Heavy-duty excavator perfect for large construction projects. Features advanced hydraulic system and comfortable operator cabin.',
                'price' => 245000.00,
                'daily_rate' => 450.00,
                'weekly_rate' => 2800.00,
                'monthly_rate' => 10500.00,
                'condition' => 'used',
                'availability_type' => 'both',
                'brand' => 'Caterpillar',
                'model' => '320D',
                'year' => 2019,
                'fuel_type' => 'diesel',
                'location' => 'Houston, TX',
                'specifications' => [
                    'Operating Weight' => '22,500 kg',
                    'Engine Power' => '153 hp',
                    'Max Digging Depth' => '6.7 m',
                    'Bucket Capacity' => '1.2 m³'
                ],
            ],
            [
                'name' => 'John Deere 850K Dozer',
                'description' => 'Powerful bulldozer with excellent pushing capability and fuel efficiency. Ideal for earthmoving and grading operations.',
                'price' => 380000.00,
                'daily_rate' => 650.00,
                'weekly_rate' => 4200.00,
                'monthly_rate' => 16000.00,
                'condition' => 'new',
                'availability_type' => 'both',
                'brand' => 'John Deere',
                'model' => '850K',
                'year' => 2023,
                'fuel_type' => 'diesel',
                'location' => 'Dallas, TX',
                'specifications' => [
                    'Operating Weight' => '19,200 kg',
                    'Engine Power' => '215 hp',
                    'Blade Capacity' => '4.1 m³',
                    'Ground Pressure' => '9.0 psi'
                ],
            ],
            // Agriculture Machinery
            [
                'name' => 'New Holland T7.315 Tractor',
                'description' => 'High-performance agricultural tractor with advanced CVT transmission. Perfect for large-scale farming operations.',
                'price' => 285000.00,
                'daily_rate' => 520.00,
                'weekly_rate' => 3400.00,
                'monthly_rate' => 13000.00,
                'condition' => 'new',
                'availability_type' => 'both',
                'brand' => 'New Holland',
                'model' => 'T7.315',
                'year' => 2024,
                'fuel_type' => 'diesel',
                'location' => 'Des Moines, IA',
                'specifications' => [
                    'Engine Power' => '315 hp',
                    'Transmission' => 'CVT Auto Command',
                    'PTO Power' => '271 hp',
                    'Lift Capacity' => '10,886 kg'
                ],
            ],
            [
                'name' => 'Case IH Axial-Flow 250 Combine',
                'description' => 'Advanced combine harvester with superior grain quality and exceptional capacity. Features automated controls.',
                'price' => 650000.00,
                'daily_rate' => 1200.00,
                'weekly_rate' => 7500.00,
                'monthly_rate' => 28000.00,
                'condition' => 'used',
                'availability_type' => 'rent',
                'brand' => 'Case IH',
                'model' => 'Axial-Flow 250',
                'year' => 2021,
                'fuel_type' => 'diesel',
                'location' => 'Lincoln, NE',
                'specifications' => [
                    'Engine Power' => '473 hp',
                    'Grain Tank' => '14,100 L',
                    'Cleaning Area' => '5.39 m²',
                    'Unloading Rate' => '145 L/s'
                ],
            ],
            // Industrial Equipment
            [
                'name' => 'Komatsu PC400 Wheel Loader',
                'description' => 'Heavy-duty wheel loader designed for quarrying, mining, and heavy construction applications.',
                'price' => 420000.00,
                'daily_rate' => 750.00,
                'weekly_rate' => 4800.00,
                'monthly_rate' => 18500.00,
                'condition' => 'used',
                'availability_type' => 'both',
                'brand' => 'Komatsu',
                'model' => 'PC400',
                'year' => 2020,
                'fuel_type' => 'diesel',
                'location' => 'Phoenix, AZ',
                'specifications' => [
                    'Operating Weight' => '39,600 kg',
                    'Engine Power' => '357 hp',
                    'Bucket Capacity' => '6.2 m³',
                    'Dump Height' => '2.9 m'
                ],
            ],
            // Material Handling
            [
                'name' => 'Toyota 8FGU35 Forklift',
                'description' => 'Reliable propane forklift with 8,000 lb capacity. Perfect for warehouse and industrial applications.',
                'price' => 45000.00,
                'daily_rate' => 85.00,
                'weekly_rate' => 520.00,
                'monthly_rate' => 1950.00,
                'condition' => 'used',
                'availability_type' => 'both',
                'brand' => 'Toyota',
                'model' => '8FGU35',
                'year' => 2018,
                'fuel_type' => 'propane',
                'location' => 'Atlanta, GA',
                'specifications' => [
                    'Lift Capacity' => '3,500 kg',
                    'Max Lift Height' => '6.5 m',
                    'Fork Length' => '1.22 m',
                    'Turning Radius' => '2.4 m'
                ],
            ],
        ];

        foreach ($machineryData as $index => $data) {
            // Assign to different categories and sellers
            $categoryIndex = $index % $categories->count();
            $sellerIndex = $index % $sellers->count();
            
            Machinery::create(array_merge($data, [
                'seller_id' => $sellers[$sellerIndex]->id,
                'category_id' => $categories[$categoryIndex]->id,
                'slug' => Str::slug($data['name']) . '-' . uniqid(),
                'is_available' => true,
                'view_count' => rand(10, 500),
                'status' => 'active',
                'latitude' => 32.7767 + (rand(-500, 500) / 100), // Approximate US coordinates
                'longitude' => -96.7970 + (rand(-500, 500) / 100),
            ]));
        }

        // Create additional random machinery
        for ($i = 0; $i < 20; $i++) {
            $brands = ['Caterpillar', 'John Deere', 'Komatsu', 'Case IH', 'New Holland', 'Bobcat', 'JCB', 'Volvo'];
            $conditions = ['new', 'used', 'refurbished'];
            $fuelTypes = ['diesel', 'electric', 'propane', 'hybrid'];
            $availabilityTypes = ['sale', 'rent', 'both'];
            $cities = ['New York, NY', 'Los Angeles, CA', 'Chicago, IL', 'Miami, FL', 'Denver, CO', 'Seattle, WA'];

            $brand = $brands[array_rand($brands)];
            $condition = $conditions[array_rand($conditions)];
            $price = rand(25000, 500000);
            $dailyRate = round($price * 0.002, 2); // Roughly 0.2% of price per day

            Machinery::create([
                'seller_id' => $sellers[array_rand($sellers->toArray())]->id,
                'category_id' => $categories[array_rand($categories->toArray())]->id,
                'name' => $brand . ' Model ' . rand(100, 999),
                'slug' => Str::slug($brand . ' Model ' . rand(100, 999)) . '-' . uniqid(),
                'description' => 'Professional grade machinery suitable for various applications. Well maintained and ready for immediate use.',
                'price' => $price,
                'daily_rate' => $dailyRate,
                'weekly_rate' => $dailyRate * 6.5,
                'monthly_rate' => $dailyRate * 25,
                'condition' => $condition,
                'availability_type' => $availabilityTypes[array_rand($availabilityTypes)],
                'is_available' => true,
                'brand' => $brand,
                'model' => 'Model ' . rand(100, 999),
                'year' => rand(2015, 2024),
                'fuel_type' => $fuelTypes[array_rand($fuelTypes)],
                'location' => $cities[array_rand($cities)],
                'view_count' => rand(5, 200),
                'status' => 'active',
                'latitude' => 39.8283 + (rand(-1000, 1000) / 100),
                'longitude' => -98.5795 + (rand(-1000, 1000) / 100),
                'specifications' => [
                    'Weight' => rand(5000, 50000) . ' kg',
                    'Power' => rand(100, 500) . ' hp',
                    'Year' => rand(2015, 2024),
                ],
            ]);
        }
    }
}