<?php

namespace App\Providers;

use App\Support\Portal;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
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
        // The app is Bootstrap-themed almost everywhere, so default pagination
        // to Bootstrap 5 markup. The few Tailwind pages (client-portal/*) opt
        // back in per-view with ->links('pagination::tailwind').
        Paginator::useBootstrapFive();

        View::composer('*', function ($view) {
            $view->with('portalHomeUrl', Portal::homeUrl());
        });
    }
}
