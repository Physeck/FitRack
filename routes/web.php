<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/mealplanning', function(){
    return view('mealplanning');
});

Route::get('/gymplanning', function(){
    return view('gymplanning');
});
