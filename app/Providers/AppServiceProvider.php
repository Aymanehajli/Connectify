<?php

namespace App\Providers;

use App\Models\Publication;
use App\Models\User;

use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Profiler\Profile;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        
        // Apply the locale from session if available
        $this->app['router']->matched(function () {
            $locale = Session::get('app_locale', config('app.locale'));
            App::setLocale($locale);
        });

        
    }
}
