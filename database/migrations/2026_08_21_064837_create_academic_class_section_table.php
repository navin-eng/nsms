<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('academic_class_section')) {
            Schema::create('academic_class_section', function (Blueprint $table) {
                $table->id();
                $table->foreignId('academic_class_id')->constrained('academic_classes')->cascadeOnDelete();
                $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
                $table->unique(['academic_class_id', 'section_id']);
            });
        }

        // Migrate existing data: if a section already has an academic_class_id, copy it to the pivot
        DB::table('sections')
            ->whereNotNull('academic_class_id')
            ->orderBy('id')
            ->each(function ($section) {
                DB::table('academic_class_section')->insertOrIgnore([
                    'academic_class_id' => $section->academic_class_id,
                    'section_id'        => $section->id,
                ]);
            });
    }

    public function down()
    {
        Schema::dropIfExists('academic_class_section');
    }
};
