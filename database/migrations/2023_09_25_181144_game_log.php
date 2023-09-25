<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_log', function (Blueprint $table) {
            $table->id();

            $table->string('chip')
                ->nullable(false);

            $table->string('salutation')
                ->nullable(false);

            $table->string('action')
                ->nullable(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_log');
    }
};
