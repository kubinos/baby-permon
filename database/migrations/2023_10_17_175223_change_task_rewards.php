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
        });

        Schema::table('task', function (Blueprint $table) {
            $table->text('response_correct')->after('points_correct');
            $table->text('response_partial')->after('points_partial');
        });
    }

    public function down(): void
    {
        Schema::table('task', function (Blueprint $table) {
            $table->dropColumn('response_correct');
            $table->dropColumn('response_partial');
        });

        Schema::table('task', function (Blueprint $table) {
            $table->string('response_number')
                ->nullable();

            $table->string('response_color')
                ->nullable();

            $table->string('response_shape')
                ->nullable();
        });
    }
};
