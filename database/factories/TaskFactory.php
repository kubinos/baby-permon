<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'difficulty' => $this->faker->randomElement(\App\Enums\Difficulty::cases()),
            'response_number' => $this->faker->randomElement(\App\Enums\Number::cases()),
            'response_color' => $this->faker->randomElement(\App\Enums\Color::cases()),
            'response_shape' => $this->faker->randomElement(\App\Enums\Shape::cases()),
            'points_correct' => $this->faker->numberBetween(8, 10),
            'points_partial' => $this->faker->numberBetween(2, 7),
            'points_incorrect' => $this->faker->numberBetween(0, 1),
        ];
    }
}
