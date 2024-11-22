<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function(){
    return view('home');
});

Route::get('/aboutus', function(){
    return view('aboutus');
});

Route::get('/onlinecoaching', function(){
    return view('onlinecoaching');
});

Route::get('/onlinecoaching', [PageController::class,'showCoaches']);

Route::get('/mealplanning', function(){
    return view('mealplanning');
});

Route::get('/gymplanning', function(){
    return view('gymplanning');
});

Route::get('/coaches/search', [PageController::class, 'search'])->name('coaches.search');


