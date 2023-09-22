<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task', function (Blueprint $table) {
            $table->id();

            $table->string('name')
                ->nullable(false);

            $table->integer('difficulty')
                ->unsigned()
                ->nullable(false);

            $table->unsignedBigInteger('station_id')
                ->nullable(false);

            $table->foreign('station_id')
                ->references('id')
                ->on('station')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // czech sound

            $table->unsignedBigInteger('sound_cs_id')
                ->nullable();

            $table->foreign('sound_cs_id')
                ->references('id')
                ->on('sound')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // english sound

            $table->unsignedBigInteger('sound_en_id')
                ->nullable();

            $table->foreign('sound_en_id')
                ->references('id')
                ->on('sound')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // german sound

            $table->unsignedBigInteger('sound_de_id')
                ->nullable();

            $table->foreign('sound_de_id')
                ->references('id')
                ->on('sound')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // responses

            $table->string('response_number')
                ->nullable();

            $table->string('response_color')
                ->nullable();

            $table->string('response_shape')
                ->nullable();

            // points (correct, partial, incorrect)

            $table->integer('points_correct')
                ->unsigned()
                ->nullable(false);

            $table->integer('points_partial')
                ->unsigned()
                ->nullable(false);

            $table->integer('points_incorrect')
                ->unsigned()
                ->nullable(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task');
    }
};
