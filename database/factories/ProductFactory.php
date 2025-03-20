<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $category = Category::inRandomOrder()->first();
        $brand = Brand::inRandomOrder()->first();
        $name = $this->generateProductName($category, $brand);

        // Generate unique SKU using category SKU + timestamp + random string
        $uniqueString = substr(time(), -5) . strtoupper(Str::random(3));
        $sku = $category->sku . $uniqueString;

        return [
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => $name,
            'slug' => Str::slug($name),
            'sku' => $sku, // Will generate something like "GHS12345ABC"
            'description' => $this->faker->paragraphs(3, true),
            'price' => $this->faker->randomFloat(2, 100, 5000),
            'stock' => $this->faker->numberBetween(0, 100),
            'warranty' => $this->faker->randomElement(['1 Year', '2 Years', '3 Years', '5 Years']),
            'product_images' => [$this->faker->imageUrl()],
        ];
    }

    private function generateProductName($category, $brand): string
    {
        $models = [
            'Processors' => ['Ryzen 5 5600X', 'Ryzen 7 5800X', 'Ryzen 9 5900X', 'Core i5-12600K', 'Core i7-12700K', 'Core i9-12900K'],
            'Graphics Cards' => ['RTX 3060', 'RTX 3070', 'RTX 3080', 'RX 6600 XT', 'RX 6700 XT', 'RX 6800 XT'],
            'Memory (RAM)' => ['Vengeance LPX 16GB', 'Trident Z RGB 32GB', 'Ripjaws V 16GB', 'Fury Beast 32GB'],
            'Storage' => ['970 EVO Plus 1TB', 'FireCuda 2TB', 'Blue SN550 1TB', 'Iron Wolf 4TB'],
        ];

        if (isset($models[$category->name])) {
            return $brand->name . ' ' . $this->faker->randomElement($models[$category->name]);
        }

        return $brand->name . ' ' . $category->name . ' ' . $this->faker->word();
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            // Get specifications for the product's category
            $specifications = $product->category->specifications;

            // For each specification, create a product specification with a value
            foreach ($specifications as $spec) {
                $value = $this->generateSpecificationValue($spec->name);
                $product->specifications()->attach($spec->id, ['value' => $value]);
            }
        });
    }

    private function generateSpecificationValue($specName): string
    {
        $specValues = [
            'Core Count' => ['4', '6', '8', '12', '16'],
            'Memory Size' => ['4GB', '8GB', '16GB', '32GB'],
            'Storage Capacity' => ['256GB', '512GB', '1TB', '2TB'],
            'Speed' => ['2666MHz', '3200MHz', '3600MHz', '4000MHz'],
            'Interface Type' => ['PCIe 3.0', 'PCIe 4.0', 'SATA III'],
            // Add more specification values as needed
        ];

        if (isset($specValues[$specName])) {
            return $this->faker->randomElement($specValues[$specName]);
        }

        return $this->faker->word();
    }
}
