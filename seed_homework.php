<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Homework;
use Carbon\Carbon;

if (Homework::count() == 0) {
    Homework::create([
        'title' => 'Maths Assignment 1',
        'description' => 'Complete chapters 1 to 5 exercises.',
        'class_id' => 1,
        'section_id' => 1,
        'subject_id' => 1,
        'due_date' => Carbon::now()->addDays(2),
        'status' => 'Active',
    ]);
    echo "Homework seeded successfully.\n";
} else {
    echo "Homework already exists.\n";
}
