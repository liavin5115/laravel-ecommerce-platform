<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('order_id');
            $table->string('gateway'); // stripe, paypal, manual
            $table->string('transaction_id')->unique()->nullable();
            $table->string('status')->default('pending'); // pending, paid, failed, refunded
            $table->decimal('amount', 10, 2);
            $table->json('gateway_payload')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            
            $table->index(['status', 'paid_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('payments'); }
};