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
    Schema::create('delivery_pricing_slabs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('hotelier_id')->constrained('hotelier_profiles')->onDelete('cascade');
        $table->decimal('min_km', 5, 2);
        $table->decimal('max_km', 5, 2);
        $table->decimal('delivery_charge', 10, 2);
        $table->integer('estimated_time_min')->default(30);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_pricing_slabs');
    }
};
