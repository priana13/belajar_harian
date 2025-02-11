<?php

namespace App\Providers;


use Filament\Facades\Filament;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
        // StickyHeader::setTheme('floating');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Schema::defaultStringLength(191);

        Filament::serving(function () {

            // Using Vite
            Filament::registerViteTheme('resources/css/filament.css'); 
         
        });

        Filament::registerRenderHook(
            'body.start',
            fn (): string => "<x-impersonate::banner :display='sdsd'/>",
        );
    }
}
