<?php

namespace Database\Factories;

use App\Enums\Color;
use App\Enums\Difficulty;
use App\Enums\Number;
use App\Enums\Shape;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
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
            'difficulty' => $this->faker->randomElement(Difficulty::cases()),
            'response_number' => $this->faker->randomElement(Number::cases()),
            'response_color' => $this->faker->randomElement(Color::cases()),
            'response_shape' => $this->faker->randomElement(Shape::cases()),
            'points_correct' => $this->faker->numberBetween(8, 10),
            'points_partial' => $this->faker->numberBetween(2, 7),
            'points_incorrect' => $this->faker->numberBetween(0, 1),
        ];
    }
}
