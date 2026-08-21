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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inventory_category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('sku_code')->nullable();
            $table->string('unit')->default('pcs'); // pcs, kg, boxes, etc.
            $table->text('description')->nullable();
            $table->integer('current_stock')->default(0); // Cached total stock based on purchases/issues/maintenance

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
        Schema::dropIfExists('inventory_items');
    }
};
