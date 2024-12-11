<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup.post');

Route::get('/signin', [AuthController::class, 'showSignin'])->name('signin');
Route::post('/signin', [AuthController::class, 'signin'])->name('signin.post');
Route::post('/signout', [AuthController::class, 'signout'])->name('signout');

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

// Route::get('/gymplanning', function () {
//     return view('gymplanning');
// });

Route::get('/profile', function () {
    return view('profile');
});
Route::post('/profile/updateProfilePage', [UserController::class, 'updateProfilePage'])->name('profile.updateProfilePage');
Route::post('/profile/updateProfile', [UserController::class, 'editProfile'])->name('profile.updateProfile');
Route::post('/profile/verifyPassword', [UserController::class, 'verifyPassword'])->name('profile.verifyPassword');
Route::post('/profile/updatePassword', [UserController::class, 'updatePassword'])->name('profile.updatePassword');
Route::get('/gymplanning', [UserController::class, 'showGymPlanner'])->name('gymplanning');
Route::post('/update_fitness_goal', [UserController::class, 'updateFitnessGoal'])->name('update_fitness_goal');
Route::get('/mealplanning', [UserController::class, 'showMealPlanner'])->name('mealplanning');
Route::post('/update_meal_preference', [UserController::class, 'updateMealPreference'])->name('update_meal_preference');
Route::get('/onlinecoaching', [UserController::class, 'searchVideos'])->name('onlinecoaching');
// Route::get('/videos', [UserController::class, 'searchVideos']);

