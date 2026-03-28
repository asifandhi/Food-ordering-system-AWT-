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
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->enum('role', ['customer', 'hotelier', 'admin'])->default('customer');
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');
        $table->string('phone', 15)->nullable();
        $table->string('profile_image')->nullable();
        $table->enum('status', ['active', 'blocked'])->default('active');
        $table->rememberToken();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
