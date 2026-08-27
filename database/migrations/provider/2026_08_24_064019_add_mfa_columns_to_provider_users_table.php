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
        Schema::table('provider_users', function (Blueprint $table) {
            $table->string('mfa_method')->default('email')->after('password');
            $table->text('totp_secret')->nullable()->after('mfa_method');
            $table->string('two_factor_code')->nullable()->after('totp_secret');
            $table->dateTime('two_factor_expires_at')->nullable()->after('two_factor_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('provider_users', function (Blueprint $table) {
            $table->dropColumn([
                'mfa_method',
                'totp_secret',
                'two_factor_code',
                'two_factor_expires_at'
            ]);
        });
    }
};
