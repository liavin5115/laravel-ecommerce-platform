<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('order_coupon', function (Blueprint $table) {
            $table->uuid('order_id');
            $table->uuid('coupon_id');
            $table->timestamps();

            $table->primary(['order_id', 'coupon_id']);
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->foreign('coupon_id')->references('id')->on('coupons')->cascadeOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('order_coupon'); }
};