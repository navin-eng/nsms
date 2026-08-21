<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('fee_invoices', function (Blueprint $table) {
            $table->decimal('previous_due', 10, 2)->default(0)->after('paid_amount')->comment('Sum of unpaid dues at the time of invoice generation');
            $table->text('remarks')->nullable()->after('previous_due');
        });
    }

    public function down()
    {
        Schema::table('fee_invoices', function (Blueprint $table) {
            $table->dropColumn(['previous_due', 'remarks']);
        });
    }
};
