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
        Schema::table('journal_entry_items', function (Blueprint $table) {
            $table->boolean('is_reconciled')->default(false);
            $table->timestamp('reconciled_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journal_entry_items', function (Blueprint $table) {
            $table->dropColumn(['is_reconciled', 'reconciled_at']);
        });
    }
};
