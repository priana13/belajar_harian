<?php

namespace App\Providers;


use App\Services\TelegramBotClient;
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

        $this->app->singleton(TelegramBotClient::class, fn () => new TelegramBotClient(
            config('services.telegram.bot_token'),
        ));
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
            // Filament::registerViteTheme('resources/css/filament.css'); 
         
        });

        // Filament::registerRenderHook(
        //     'body.start',
        //     fn (): string => "<x-impersonate::banner :display='sdsd'/>",
        // );
    }
}
