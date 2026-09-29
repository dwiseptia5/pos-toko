<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => fake()->words(2, true),
            'sku' => fake()->unique()->bothify('SKU-####'),
            'price' => fake()->numberBetween(5000, 100000),
            'stock' => fake()->numberBetween(1, 100),
        ];
    }
}
