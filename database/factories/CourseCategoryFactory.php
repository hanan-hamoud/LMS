<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseCategory>
 */
class CourseCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => [
                'en' => $this->faker->unique()->words(2, true),
                'ar' => 'تصنيف ' . $this->faker->unique()->randomNumber(3),
            ],
            'slug' => fake()->slug(),
            'status' => $this->faker->boolean,
        ];
    }
}
