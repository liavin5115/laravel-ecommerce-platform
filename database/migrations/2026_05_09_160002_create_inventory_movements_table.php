<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('product_variant_id');
            $table->string('movement_type'); // purchase, refund, restock, adjustment, cancellation
            $table->integer('quantity');
            $table->string('reference_type')->nullable();
            $table->uuid('reference_id')->nullable();
            $table->timestamps();

            $table->foreign('product_variant_id')->references('id')->on('product_variants')->cascadeOnDelete();
            
            $table->index(['product_variant_id', 'created_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('inventory_movements'); }
};