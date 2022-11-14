<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'parent_category_id' => null,
            'name' => $this->faker->unique()->word,
        ];
    }
}
