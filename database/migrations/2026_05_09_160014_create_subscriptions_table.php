<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organization_id');
            $table->string('provider'); // stripe, braintree, manual
            $table->string('provider_subscription_id')->unique()->nullable();
            $table->string('status')->default('active'); // active, canceled, past_due
            $table->decimal('monthly_price', 10, 2);
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('renews_at')->nullable();
            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('organizations')->cascadeOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('subscriptions'); }
};