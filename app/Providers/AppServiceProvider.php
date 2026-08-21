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
        $this->app->bind(
            \App\Services\Communication\SmsGatewayInterface::class,
            function ($app) {
                try {
                    $config = \App\Models\CommunicationConfig::activeFor('sms');
                    if ($config && $config->driver === 'sparrow') {
                        return new \App\Services\Communication\Gateways\SparrowSmsGateway();
                    } elseif ($config && $config->driver === 'ntc') {
                        return new \App\Services\Communication\Gateways\NtcSmsGateway();
                    }
                } catch (\Throwable $e) {
                    // Fallback to dummy if database not ready or table missing
                }
                return new \App\Services\Communication\Gateways\DummySmsGateway();
            }
        );
        $this->app->bind(
            \App\Services\Communication\PushGatewayInterface::class,
            \App\Services\Communication\Gateways\FcmPushGateway::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Illuminate\Pagination\Paginator::useBootstrap();
        
        // Register Activity Logs Observer for important models
        $modelsToObserve = [
            \App\Models\Staff::class,
            \App\Models\Student::class,
            \App\Models\SiteSetting::class,
            \App\Models\User::class,
            \Spatie\Permission\Models\Role::class,
            \App\Models\AcademicClass::class,
            \App\Models\Section::class,
            \App\Models\AcademicYear::class,
        ];

        foreach ($modelsToObserve as $model) {
            $model::observe(\App\Observers\ActivityObserver::class);
        }

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
