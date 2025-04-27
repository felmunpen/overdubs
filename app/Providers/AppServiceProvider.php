<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

//
use Illuminate\Support\Facades\Route;
// use App\Http\Middleware\UsertypeCheck;
use App\Http\Middleware\AdminCheck;
use App\Http\Middleware\NotArtistCheck;
//

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
        //
        if (config('app.env') === 'production') {
            \URL::forceScheme('https');
        }
        //
        Route::aliasMiddleware('admin.check', AdminCheck::class);

        Route::aliasMiddleware('notArtist.check', NotArtistCheck::class);

    }
}
