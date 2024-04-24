<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
// Gate = is a closure that datermine if a user is authorized to perform action
use Illuminate\Support\Facades\Gate;
use App\Models\User; //represents the users table

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
        Paginator::useBootstrap();

        Gate::define('admin', function($user){
            return $user->role_id === User::ADMIN_ROLE_ID;//ADMIN_ROLE_IDのユーザーのみ、Admin部分にエンターできる
        });
    }
}
