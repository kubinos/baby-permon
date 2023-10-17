<?php

namespace Database\Factories;

use App\Enums\Emotion;
use App\Enums\GameType;
use App\Enums\Language;
use App\Enums\Level;
use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Game>
 */
class GameFactory extends Factory
{
    protected $model = Game::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'chip' => $this->faker->unique()->uuid,
            'type' => $this->faker->randomElement(GameType::cases()),
            'salutation' => $this->faker->randomElement(['Mr', 'Mrs']),
            'level' => $this->faker->randomElement(Level::cases()),
            'emotion' => $this->faker->randomElement(Emotion::cases()),
            'language' => $this->faker->randomElement(Language::cases()),
            'points' => $this->faker->numberBetween(0, 100),
            'created_at' => $this->faker->dateTimeBetween('-2 hours', 'now')->format('Y-m-d H:i:s'),
        ];
    }
}
