<?php

namespace Database\Factories;

use App\Models\Sound;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sound>
 */
class SoundFactory extends Factory
{
    protected $model = Sound::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->colorName,
            'number' => sprintf('%d.wav', $this->faker->unique()->numberBetween(0, 100)),
        ];
    }
}
