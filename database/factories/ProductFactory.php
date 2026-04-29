<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [

            'name' => ucfirst($this->faker->words(2, true)) . ' Pro',
            'brand' => $this->faker->randomElement(['Apple', 'Samsung', 'Sony', 'Dell', 'HP', 'Lenovo']),
            'category' => $this->faker->randomElement(['Smartphones', 'Laptops', 'Tablets', 'Accessories']),
            'price' => $this->faker->randomFloat(2, 500, 7000),
            'stock' => $this->faker->numberBetween(10, 100),
            'description' => $this->faker->sentence(8),
            'image' => 'https://placehold.co/400x300?text=TechStore',
        ];
    }
}