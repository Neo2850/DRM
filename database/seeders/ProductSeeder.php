<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Generate 20 random products
        Product::factory(20)->create();

        // Generate 10 processors
        $processorCategory = Category::where('name', 'Processors')->first();
        if ($processorCategory) {
            Product::factory(10)
                ->state(['category_id' => $processorCategory->id])
                ->create();
        }

        // Generate 10 graphics cards
        $gpuCategory = Category::where('name', 'Graphics Cards')->first();
        if ($gpuCategory) {
            Product::factory(10)
                ->state(['category_id' => $gpuCategory->id])
                ->create();
        }

        // Generate 10 memory products
        $ramCategory = Category::where('name', 'Memory (RAM)')->first();
        if ($ramCategory) {
            Product::factory(10)
                ->state(['category_id' => $ramCategory->id])
                ->create();
        }
    }
}
