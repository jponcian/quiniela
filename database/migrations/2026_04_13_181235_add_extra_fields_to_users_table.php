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
        Schema::table('users', function (Blueprint $table) {
            $table->string('cedula')->unique()->after('id');
            $table->string('lastname')->after('name');
            $table->string('whatsapp')->unique()->after('email');
            $table->integer('points')->default(0)->after('password');
            $table->enum('role', ['user', 'admin'])->default('user')->after('points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cedula', 'lastname', 'whatsapp', 'points', 'role']);
        });
    }
};
