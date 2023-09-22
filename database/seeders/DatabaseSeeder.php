<?php

namespace Database\Seeders;

use App\Models\Config;
use App\Models\Sound;
use App\Models\Station;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Config::query()
            ->create([
                'threshold_level_1' => 10,
                'threshold_level_2' => 20,
                'threshold_level_3' => 30,
            ]);

        Station::factory()
            ->count(10)
            ->has(
                Task::factory(3)
                    ->for(
                        Sound::factory(),
                        'soundCs',
                    )
                    ->for(
                        Sound::factory(),
                        'soundEn',
                    )
                    ->for(
                        Sound::factory(),
                        'soundDe',
                    )
            )
            ->create();
    }
}
