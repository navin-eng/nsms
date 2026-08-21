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
        Schema::create('inventory_purchases', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inventory_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inventory_supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('inventory_store_id')->nullable()->constrained()->nullOnDelete();
            
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2)->default(0);
            
            $table->date('purchase_date');
            $table->string('invoice_number')->nullable();
            $table->string('attachment')->nullable();
            
            $table->text('note')->nullable();

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
        Schema::dropIfExists('inventory_purchases');
    }
};
