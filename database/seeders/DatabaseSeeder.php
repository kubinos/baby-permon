<?php

namespace Database\Seeders;

use App\Models\Config;
use App\Models\Sound;
use App\Models\Station;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Config::query()
            ->create([
                'threshold_level_1' => 10,
                'threshold_level_2' => 20,
                'threshold_level_3' => 30,
            ]);

        Sound::factory()
            ->count(20)
            ->create();

        Station::factory()
            ->count(10)
            ->create();
    }
}
