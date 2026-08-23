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
        \App\Models\Accountant::create([
            'name' => 'Head Accountant',
            'email' => 'accountant@school.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
    }
}
