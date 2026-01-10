<?php

namespace App\Providers;

use App\Models\Genre;
use Illuminate\Pagination\Paginator;
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
        Paginator::useBootstrap();
        view()->composer('layouts.main', function ($view) {
            $genres = Genre::withCount('movies')->orderBy('name')->get()->map(function ($genre) {
                    // Используем mb_convert_case для корректной работы с русскими буквами
                    $genre->name = mb_convert_case($genre->name, MB_CASE_TITLE, 'UTF-8');
                    return $genre;
                });

            $view->with('genres', $genres);
        });
    }
}
