<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
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
    }
}
