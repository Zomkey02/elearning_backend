<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
    public function boot(): void
    {
        Gate::define('manage-all', fn ($user) => $user->role === 'admin');
        Gate::define('manage-blog', fn ($user) => in_array($user->role, ['admin', 'writer']));
        Gate::define('user-access', fn ($user) => $user->role !== null);


    }
}
