<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Illuminate\Support\Facades\View::composer('frontend.layout.header', function ($view) {
            $navbarItems = \App\Models\NavbarItem::whereNull('parent_id')
                ->with(['children' => function($q) {
                    $q->orderBy('order');
                }])
                ->orderBy('order')
                ->get();
            $navCourses = \App\Models\Course::where('status', 1)->get();
            $view->with('navbarItems', $navbarItems)->with('navCourses', $navCourses);
        });
    }
}
