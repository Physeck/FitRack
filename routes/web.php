<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/signin', function () {
    return view('signin');
});

Route::get('/signup', function () {
    return view('signup');
});

Route::get('/', function () {
    return view('home');
});

Route::get('/aboutus', function () {
    return view('aboutus');
});

Route::get('/onlinecoaching', function () {
    return view('onlinecoaching');
});

Route::get('/mealplanning', function () {
    return view('mealplanning');
});

Route::get('/gymplanning', function () {
    return view('gymplanning');
});

// Route::get('/coaches/search', [PageController::class, 'search'])->name('coaches.search');
