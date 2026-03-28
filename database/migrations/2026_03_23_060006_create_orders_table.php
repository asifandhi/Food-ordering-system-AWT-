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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('hotelier_id')->constrained('hotelier_profiles')->onDelete('cascade');
        $table->decimal('total_amount', 10, 2);
        $table->decimal('delivery_charge', 10, 2)->default(0.00);
        $table->decimal('grand_total', 10, 2);
        $table->decimal('delivery_lat', 10, 8)->nullable();
        $table->decimal('delivery_lng', 11, 8)->nullable();
        $table->decimal('distance_km', 6, 2)->nullable();
        $table->integer('estimated_delivery_time')->nullable();
        $table->text('delivery_address');
        $table->enum('status', [
            'pending','confirmed','preparing',
            'out_for_delivery','delivered','cancelled'
        ])->default('pending');
        $table->enum('payment_method', ['cod', 'online'])->default('cod');
        $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
