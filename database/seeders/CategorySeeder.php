<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Construction Equipment',
                'slug' => 'construction-equipment',
                'description' => 'Heavy machinery for construction projects including excavators, bulldozers, and cranes.',
                'icon' => 'hard-hat',
                'is_active' => true,
            ],
            [
                'name' => 'Agriculture Machinery',
                'slug' => 'agriculture-machinery',
                'description' => 'Farm equipment including tractors, harvesters, and plowing equipment.',
                'icon' => 'tractor',
                'is_active' => true,
            ],
            [
                'name' => 'Industrial Equipment',
                'slug' => 'industrial-equipment',
                'description' => 'Manufacturing and industrial machinery for various industrial applications.',
                'icon' => 'industry',
                'is_active' => true,
            ],
            [
                'name' => 'Heavy Duty Trucks',
                'slug' => 'heavy-duty-trucks',
                'description' => 'Commercial trucks and heavy-duty vehicles for transportation and logistics.',
                'icon' => 'truck',
                'is_active' => true,
            ],
            [
                'name' => 'Material Handling',
                'slug' => 'material-handling',
                'description' => 'Forklifts, conveyor systems, and other material handling equipment.',
                'icon' => 'boxes',
                'is_active' => true,
            ],
            [
                'name' => 'Power Generation',
                'slug' => 'power-generation',
                'description' => 'Generators, power systems, and electrical equipment.',
                'icon' => 'bolt',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}