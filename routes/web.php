<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::name('main.')->group(function () {

    Route::get('/', \App\Http\Controllers\Main\IndexController::class)->name('index');

    Route::get('/filter', \App\Http\Controllers\Main\FilterController::class)->name('filter');
    Route::get('/genres/{genre}', \App\Http\Controllers\Main\GenreController::class)->name('genres');
    Route::get('/api/search', \App\Http\Controllers\Main\SearchController::class)
        ->name('api.search');

    Route::prefix('movies')->name('movies.')->group(function () {
        Route::get('/{movie}', \App\Http\Controllers\Main\ShowController::class)->name('show');
    });

    Route::middleware('auth')->group(function () {
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', \App\Http\Controllers\Profile\IndexController::class)->name('index');
            Route::get('/edit', \App\Http\Controllers\Profile\EditController::class)->name('edit');
            Route::put('/update', \App\Http\Controllers\Profile\UpdateController::class)->name('update');
            Route::prefix('password')->name('password.')->group(function () {
                Route::get('/edit',\App\Http\Controllers\Profile\Password\EditController::class)->name('edit');
                Route::put('/update',\App\Http\Controllers\Profile\Password\UpdateController::class)->name('update');
            });
            Route::get('/likes', \App\Http\Controllers\Profile\Likes\IndexController::class )->name('likes');
            Route::post('/likes/{movie}/toggle',\App\Http\Controllers\Profile\Likes\ToggleLike::class)->name('likes.toggle');
            Route::prefix('comments')->name('comments.')->group(function () {
                Route::get('/', \App\Http\Controllers\Profile\Comments\IndexController::class)->name('index');
                Route::post('/', \App\Http\Controllers\Profile\Comments\StoreController::class)->name('store');
                Route::delete('/{comment}', \App\Http\Controllers\Profile\Comments\DeleteController::class)
                    ->name('delete');
            });

        });
    });

});

Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function(){
Route::name('main.')->group(function(){
    Route::get('/', \App\Http\Controllers\Admin\Main\IndexController::class )->name('index');
});

    // AJAX роуты для дашборда
    Route::prefix('dashboard')->name('dashboard.')->group(function(){
        Route::get('/widgets', [\App\Http\Controllers\Admin\Main\IndexController::class, 'getWidgetsData'])
            ->name('widgets');
        Route::get('/growth', [\App\Http\Controllers\Admin\Main\IndexController::class, 'getGrowthData'])
            ->name('growth');
        Route::get('/refresh', [\App\Http\Controllers\Admin\Main\IndexController::class, 'refreshStats'])
            ->name('refresh');
    });

Route::prefix('genres')->name('genres.')->group(function(){
   Route::get('/', \App\Http\Controllers\Admin\Genre\IndexController::class )->name('index');
   Route::get('/create', \App\Http\Controllers\Admin\Genre\CreateController::class)->name('create');
   Route::post('/', \App\Http\Controllers\Admin\Genre\StoreController::class)->name('store');
    Route::get('/{genre}/edit}', \App\Http\Controllers\Admin\Genre\EditController::class)->name("edit");
    Route::put('/{genre}', \App\Http\Controllers\Admin\Genre\UpdateController::class)
        ->name('update');
    Route::delete('/{genre}', \App\Http\Controllers\Admin\Genre\DeleteController::class)
        ->name('delete');
});

Route::prefix('movies')->name('movies.')->group(function(){
    Route::get('/', \App\Http\Controllers\Admin\Movie\IndexController::class)->name('index');
    Route::get('/create', \App\Http\Controllers\Admin\Movie\CreateController::class)->name("create");
    Route::post('/', \App\Http\Controllers\Admin\Movie\StoreController::class)->name("store");
    Route::get('/{movie}/edit', \App\Http\Controllers\Admin\Movie\EditController::class)->name("edit");
    Route::get('/{movie}', \App\Http\Controllers\Admin\Movie\ShowController::class)->name("show");
    Route::patch('/{movie}', \App\Http\Controllers\Admin\Movie\UpdateController::class)->name("update");
    Route::delete('/{movie}', \App\Http\Controllers\Admin\Movie\DeleteController::class)->name("delete");
});

    Route::prefix('actors')->name('actors.')->group(function () {
        Route::get('/', \App\Http\Controllers\Admin\Actor\IndexController::class)->name('index');
        Route::get('/create', \App\Http\Controllers\Admin\Actor\CreateController::class)->name('create');
        Route::post('/', \App\Http\Controllers\Admin\Actor\StoreController::class)->name('store');
        Route::get('/{actor}/edit}', \App\Http\Controllers\Admin\Actor\EditController::class)->name("edit");

        Route::patch('/{actor}', \App\Http\Controllers\Admin\Actor\UpdateController::class)
            ->name('update');

        Route::delete('/{actor}', \App\Http\Controllers\Admin\Actor\DeleteController::class)
            ->name('delete');
    });

    Route::prefix('users')->name('users.')->group(function () {

        Route::get('/', \App\Http\Controllers\Admin\User\IndexController::class)->name('index');
        Route::get('/create', \App\Http\Controllers\Admin\User\CreateController::class)->name('create');
        Route::post('/', \App\Http\Controllers\Admin\User\StoreController::class)->name('store');


        Route::get('/{user}', \App\Http\Controllers\Admin\User\ShowController::class)->name('show');

        Route::get('/{user}/edit', \App\Http\Controllers\Admin\User\EditController::class)->name('edit');
        Route::patch('/{user}', \App\Http\Controllers\Admin\User\UpdateController::class)->name('update');
        Route::delete('/{user}', \App\Http\Controllers\Admin\User\DeleteController::class)->name('delete');

    });
});



Auth::routes();
