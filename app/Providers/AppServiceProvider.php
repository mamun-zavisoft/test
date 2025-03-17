<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;

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
        View::addNamespace('RolePermission', base_path('app/Modules/RolePermission/Views'));


        View::composer('*', function () {

            Blade::if('permission', function ($permissions) {
                if (is_array($permissions)) {
                    foreach ($permissions as $permission) {
                        if (Auth::check() && Auth::user()->hasPermission($permission)) {
                            return true;
                        }
                    }
                }
                return Auth::check() && Auth::user()->hasPermission($permissions);
            });

            Blade::directive('elsepermission', function () {
                return '<?php else: ?>';
            });
        });
    }
}
