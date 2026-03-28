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
    Schema::create('food_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('hotelier_id')->constrained('hotelier_profiles')->onDelete('cascade');
        $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
        $table->string('name', 150);
        $table->text('description')->nullable();
        $table->decimal('price', 10, 2);
        $table->string('image')->nullable();
        $table->boolean('is_available')->default(true);
        $table->boolean('is_veg')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_items');
    }
};
