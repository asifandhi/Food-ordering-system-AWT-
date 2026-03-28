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
    Schema::create('hotelier_profiles', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->string('hotel_name', 150);
        $table->string('hotel_logo')->nullable();
        $table->string('hotel_banner')->nullable();
        $table->text('description')->nullable();
        $table->string('cuisine_type', 100)->nullable();
        $table->text('address')->nullable();
        $table->string('city', 100)->nullable();
        $table->string('pincode', 10)->nullable();
        $table->decimal('latitude', 10, 8)->nullable();
        $table->decimal('longitude', 11, 8)->nullable();
        $table->time('opening_time')->nullable();
        $table->time('closing_time')->nullable();
        $table->boolean('is_open')->default(true);
        $table->decimal('delivery_radius_km', 5, 2)->default(10.00);
        $table->decimal('base_delivery_charge', 10, 2)->default(20.00);
        $table->decimal('per_km_charge', 10, 2)->default(5.00);
        $table->decimal('free_delivery_above', 10, 2)->nullable();
        $table->decimal('max_delivery_charge', 10, 2)->nullable();
        $table->integer('avg_delivery_time')->default(30);
        $table->decimal('minimum_order', 10, 2)->default(0.00);
        $table->decimal('rating', 3, 2)->default(0.00);
        $table->string('gstin', 20)->nullable();
        $table->string('bank_account', 50)->nullable();
        $table->boolean('is_verified')->default(false);
        $table->enum('status', ['pending', 'approved', 'suspended'])->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotelier_profiles');
    }
};
