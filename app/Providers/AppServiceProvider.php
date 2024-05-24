<?php

namespace App\Providers;

use App\Models\Publication;
use App\Models\User;

use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Profiler\Profile;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;



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
        $this->registerPolicies();

        Gate::define('update-publication',function(GenericUser $user,Publication $publication){
            return $user->id === $publication->user_id;
        });

        
    }
}
