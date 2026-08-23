<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('expense_date');
            $table->string('reference')->unique();
            $table->foreignId('vendor_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('expense_account_id')->constrained('accounts')->cascadeOnDelete();
            $table->foreignId('payment_account_id')->constrained('accounts')->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->foreignId('journal_entry_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('paid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
