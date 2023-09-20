<?php

namespace Database\Seeders;

use App\Models\Sound;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Sound::factory()
            ->count(20)
            ->create();
    }
}
