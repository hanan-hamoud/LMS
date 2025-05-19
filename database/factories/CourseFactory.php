<?php

namespace Database\Factories;
use App\Models\CourseCategory;
use App\Models\Instructor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'category_id' => CourseCategory::factory(),
            'instructor_id' => Instructor::factory(),
            'status' => $this->faker->boolean,
        ];
    }
}
