<?php

namespace App\Providers;

// use Illuminate\Auth\Notifications\VerifyEmail;
// use Illuminate\Notifications\Messages\MailMessage;
// use Illuminate\Support\Facades\Lang;
// use Illuminate\Support\ServiceProvider;

use Carbon\Carbon;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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
        if (env('APP_ENV') !== 'local') {
            \URL::forceScheme('https');
        }
    }
}
