<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game', function (Blueprint $table) {
            $table->id();

            $table->string('chip')
                ->nullable(false);

            $table->string('salutation')
                ->nullable(false);

            $table->integer('level')
                ->nullable(false);

            $table->string('emotion')
                ->nullable(false);

            $table->string('language')
                ->nullable(false);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game');
    }
};
