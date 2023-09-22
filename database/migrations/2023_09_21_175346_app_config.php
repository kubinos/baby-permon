<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('config', function (Blueprint $table) {
            $table->id();

            $table->integer('threshold_level_1')
                ->unsigned()
                ->nullable(false);

            $table->integer('threshold_level_2')
                ->unsigned()
                ->nullable(false);

            $table->integer('threshold_level_3')
                ->unsigned()
                ->nullable(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('config');
    }
};
