<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up()
    {
        // SQLite doesn't support ALTER COLUMN directly.
        // Recreate the table with academic_class_id as nullable.
        DB::statement('PRAGMA foreign_keys=OFF');

        DB::statement('
            CREATE TABLE sections_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255) NOT NULL,
                academic_class_id INTEGER NULL REFERENCES academic_classes(id) ON DELETE SET NULL,
                capacity INTEGER NULL,
                created_at DATETIME NULL,
                updated_at DATETIME NULL
            )
        ');

        DB::statement('INSERT INTO sections_new (id, name, academic_class_id, capacity, created_at, updated_at)
            SELECT id, name, academic_class_id, capacity, created_at, updated_at FROM sections');

        DB::statement('DROP TABLE sections');
        DB::statement('ALTER TABLE sections_new RENAME TO sections');

        DB::statement('PRAGMA foreign_keys=ON');
    }

    public function down()
    {
        // No safe rollback for this in SQLite
    }
};
