<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('task', function (Blueprint $table) {
            $table->dropColumn('response_number');
            $table->dropColumn('response_color');
            $table->dropColumn('response_shape');
            $table->dropColumn('points_correct');
            $table->dropColumn('points_partial');
            $table->dropColumn('points_incorrect');
        });

        Schema::table('task', function (Blueprint $table) {

            $table->text('response_correct')->after('sound_de_id');
            $table->integer('points_correct')
                ->after('response_correct')
                ->unsigned()
                ->nullable(false);

            $table->text('response_partial')->after('points_correct');
            $table->integer('points_partial')
                ->after('response_partial')
                ->unsigned()
                ->nullable(false);
        });
    }

    public function down(): void
    {
        Schema::table('task', function (Blueprint $table) {
            $table->dropColumn('response_correct');
            $table->dropColumn('points_correct');

            $table->dropColumn('response_partial');
            $table->dropColumn('points_partial');
        });

        Schema::table('task', function (Blueprint $table) {
            $table->string('response_number')
                ->nullable();

            $table->string('response_color')
                ->nullable();

            $table->string('response_shape')
                ->nullable();

            $table->integer('points_correct')
                ->unsigned()
                ->nullable(false);

            $table->integer('points_partial')
                ->unsigned()
                ->nullable(false);

            $table->integer('points_incorrect')
                ->unsigned()
                ->nullable(false);
        });
    }
};
