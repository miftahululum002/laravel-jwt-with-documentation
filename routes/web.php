<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::any('{path}', function () {
//     abort(404, 'Page not found');
// })->where('path', '.*');
