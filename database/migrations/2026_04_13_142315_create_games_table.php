<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('api_id')->unique()->nullable();
            $table->string('team_a');
            $table->string('team_b');
            $table->string('flag_a')->nullable();
            $table->string('flag_b')->nullable();
            $table->integer('score_a')->nullable();
            $table->integer('score_b')->nullable();
            $table->dateTime('match_date');
            $table->string('group')->nullable();
            $table->string('round')->nullable();
            $table->string('status')->default('scheduled');
            $table->string('venue')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
