<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Accountant::firstOrCreate(
            ['email' => 'accountant@school.com'],
            [
                'name' => 'Head Accountant',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );
    }
}
