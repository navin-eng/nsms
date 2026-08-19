<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NavbarItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['title' => 'Home', 'type' => 'route', 'value' => 'home', 'order' => 1],
            ['title' => 'About Us', 'type' => 'route', 'value' => 'about.us', 'order' => 2],
            ['title' => 'Academics', 'type' => 'dynamic_courses', 'value' => null, 'order' => 3],
            ['title' => 'Faculties', 'type' => 'route', 'value' => 'member', 'order' => 4],
            ['title' => 'Calendar', 'type' => 'route', 'value' => 'calendar', 'order' => 5],
            ['title' => 'Gallery', 'type' => 'route', 'value' => 'gallery', 'order' => 6],
            ['title' => 'Contact', 'type' => 'route', 'value' => 'contact', 'order' => 7],
            ['title' => 'Apply Now', 'type' => 'route', 'value' => 'contact', 'css_class' => 'nav-apply', 'order' => 8],
        ];

        foreach ($items as $item) {
            \App\Models\NavbarItem::create($item);
        }
    }
}
