<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game', function (Blueprint $table) {
            $table->string('type')->after('chip');
            $table->timestamp('ended_at')->nullable()->after('points');
        });
    }

    public function down(): void
    {
        Schema::table('game', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('ended_at');
        });
    }
};
