<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game', function (Blueprint $table) {
            $table->unsignedBigInteger('current_task_id')
                ->nullable()
                ->after('points');

            $table->foreign('current_task_id')
                ->references('id')
                ->on('task')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });

        Schema::table('game_log', function (Blueprint $table) {
            $table->dropColumn('salutation');
        });

        Schema::table('game_log', function (Blueprint $table) {
            $table->string('type')->after('chip');
            $table->string('location')->nullable()->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('game', function (Blueprint $table) {
            $table->dropForeign(['current_task_id']);
            $table->dropColumn('current_task_id');
        });
    }
};
