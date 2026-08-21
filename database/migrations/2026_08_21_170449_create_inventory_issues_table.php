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
        Schema::create('inventory_issues', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inventory_item_id')->constrained()->cascadeOnDelete();
            
            // Polymorphic for Staff vs Department
            $table->string('issue_to_type'); // App\Models\Staff or App\Models\Department
            $table->unsignedBigInteger('issue_to_id');
            
            $table->integer('quantity');
            $table->date('issue_date');
            $table->date('due_date')->nullable();
            $table->date('return_date')->nullable();
            
            $table->string('status')->default('Issued'); // Issued, Returned, Overdue
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
        Schema::dropIfExists('inventory_issues');
    }
};
