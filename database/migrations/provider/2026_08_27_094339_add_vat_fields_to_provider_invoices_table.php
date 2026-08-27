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
    public function up()
    {
        Schema::table('provider_invoices', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->after('amount')->default(0);
            $table->decimal('tax_amount', 10, 2)->after('subtotal')->default(0);
            $table->decimal('discount', 10, 2)->after('tax_amount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('provider_invoices', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'tax_amount', 'discount']);
        });
    }
};
