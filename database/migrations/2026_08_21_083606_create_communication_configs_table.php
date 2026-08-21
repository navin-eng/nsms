<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('communication_configs', function (Blueprint $table) {
            $table->id();
            $table->string('channel')->comment('sms, email, push');
            $table->string('driver')->comment('sparrow, ntc, smtp, fcm, dummy');
            $table->json('config')->nullable()->comment('JSON blob of credentials/settings');
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->unique('channel'); // one active config per channel
        });
    }

    public function down()
    {
        Schema::dropIfExists('communication_configs');
    }
};
