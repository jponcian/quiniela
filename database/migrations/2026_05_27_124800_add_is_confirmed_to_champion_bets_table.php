<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('champion_bets', function (Blueprint $table) {
            $table->boolean('is_confirmed')->default(false)->after('team_name');
        });
    }

    public function down(): void
    {
        Schema::table('champion_bets', function (Blueprint $table) {
            $table->dropColumn('is_confirmed');
        });
    }
};
