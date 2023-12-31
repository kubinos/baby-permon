<?php

namespace Database\Factories;

use App\Enums\Difficulty;
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
            'response_correct' => ['00', '11', '22', '00'],
            'points_correct' => 10,
            'response_partial' => ['11', '22', '33', '00'],
            'points_partial' => 5,
            'points_incorrect' => 0,
        ];
    }
}
