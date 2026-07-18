<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

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
        // Define Gates for Role-Based Access Control
        Gate::define('super-admin', function (User $user) {
            return $user->isSuperAdmin();
        });

        Gate::define('admin-posyandu', function (User $user) {
            return $user->isAdminPosyandu();
        });

        Gate::define('manage-users', function (User $user) {
            return $user->isSuperAdmin(); // Only Super Admin can manage users
        });
    }
}
