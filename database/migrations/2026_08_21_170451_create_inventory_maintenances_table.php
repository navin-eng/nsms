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
        Schema::create('inventory_maintenances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inventory_item_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity');
            $table->string('type'); // Damaged, Lost, Maintenance
            $table->date('date');
            
            $table->text('description')->nullable();
            $table->decimal('cost', 10, 2)->default(0);
            $table->string('status')->default('Pending'); // Pending, Repaired, Discarded

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_maintenances');
    }
};
