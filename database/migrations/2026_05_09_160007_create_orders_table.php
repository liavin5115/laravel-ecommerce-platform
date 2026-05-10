<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organization_id');
            $table->uuid('customer_id');
            $table->uuid('address_id')->nullable();
            $table->string('order_number')->unique();
            $table->string('status')->default('pending'); // pending, processing, completed, cancelled
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_total', 10, 2)->default(0);
            $table->decimal('shipping_total', 10, 2)->default(0);
            $table->decimal('discount_total', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2);
            $table->timestamp('placed_at')->nullable();
            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('organizations')->cascadeOnDelete();
            $table->foreign('customer_id')->references('id')->on('customers')->cascadeOnDelete();
            $table->foreign('address_id')->references('id')->on('addresses')->nullOnDelete();

            $table->index(['organization_id', 'status']);
            $table->index(['customer_id', 'created_at']);
            $table->index(['placed_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('orders'); }
};