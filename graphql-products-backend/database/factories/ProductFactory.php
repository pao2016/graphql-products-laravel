<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'        => $this->faker->words(3, true),     // Ej.: “Café Premium Molido”
            'description' => $this->faker->sentence(),         // Ej.: “Café orgánico de origen único”
            'price'       => $this->faker->numberBetween(1000, 50000),
            // Crea (o reutiliza en memoria) una categoría y asigna su id
            'category_id' => Category::factory(),
        ];
    }
}
