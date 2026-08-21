<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('fee_invoices', function (Blueprint $table) {
            $table->string('nepali_month', 50)->nullable()->after('academic_year_id');
        });
    }

    public function down()
    {
        Schema::table('fee_invoices', function (Blueprint $table) {
            $table->dropColumn('nepali_month');
        });
    }
};
