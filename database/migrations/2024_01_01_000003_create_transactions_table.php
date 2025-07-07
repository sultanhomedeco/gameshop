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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique(); // e.g., TRX-2024-001
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('topup_package_id')->constrained()->onDelete('cascade');
            $table->string('player_id'); // User's game ID
            $table->string('player_name')->nullable(); // User's game name
            $table->decimal('amount', 10, 2); // Amount paid
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable(); // Admin/operator notes
            $table->timestamp('processed_at')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null'); // Admin/operator who processed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
}; 