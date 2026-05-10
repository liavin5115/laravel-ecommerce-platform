<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('support_ticket_id');
            $table->uuid('sender_user_id')->nullable(); // nullable if sent by customer, or we use polymorphic sender
            $table->text('message');
            $table->timestamps();

            $table->foreign('support_ticket_id')->references('id')->on('support_tickets')->cascadeOnDelete();
            $table->foreign('sender_user_id')->references('id')->on('users')->nullOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('ticket_messages'); }
};