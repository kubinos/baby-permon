<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sound', function (Blueprint $table) {
            $table->id();

            $table->string('name')
                ->nullable(false);

            $table->string('number')
                ->nullable(false)
                ->unsigned();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sound');
    }
};
