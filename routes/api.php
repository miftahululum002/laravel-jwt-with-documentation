<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use App\Http\Middleware\JwtMiddleware;

Route::name('api.')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login')->name('login');
        Route::post('register', 'register')->name('register');
        Route::middleware(JwtMiddleware::class)->group(function () {
            Route::post('logout', 'logout')->name('logout');
            Route::post('refresh', 'refresh')->name('refresh');
            Route::get('me', 'me')->name('me');
        });
    });

    Route::middleware(JwtMiddleware::class)->group(function () {
        Route::prefix('todos')->group(function () {
            Route::name('todos.')->group(function () {
                Route::controller(TodoController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/', 'store')->name('store');
                    Route::get('/{id}', 'show')->name('show');
                    Route::put('/{id}', 'update')->name('update');
                    Route::delete('/{id}', 'destroy')->name('destroy');
                });
            });
        });
    });
});

Route::any('{path}', function () {
    abort(404, 'Page not found');
})->where('path', '.*');
