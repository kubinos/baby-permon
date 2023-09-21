<?php

namespace Database\Seeders;

use App\Models\Config;
use App\Models\Sound;
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
    }
}
