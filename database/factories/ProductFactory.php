<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::inRandomOrder()->first()->id,
            'name' => $this->faker->words(2, true),
            'sku' => $this->faker->unique()->bothify('SKU-####'),
            'price' => $this->faker->numberBetween(2000, 50000),
            'stock' => $this->faker->numberBetween(10, 100),
        ];
    }
}

