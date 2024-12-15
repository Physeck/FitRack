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

Route::get('/signin', [AuthController::class, 'showSignin'])->name('login');
Route::post('/signin', [AuthController::class, 'signin'])->name('signin.post');
Route::post('/signout', [AuthController::class, 'signout'])->name('signout');

Route::get('/', function () {
    return view('home');
});

Route::get('/aboutus', function () {
    return view('aboutus');
});

Route::get('/profile', function () {
    return view('profile');
})->middleware(\App\Http\Middleware\ProfileMiddleWare::class);;
Route::post('/profile/updateProfilePage', [UserController::class, 'updateProfilePage'])->name('profile.updateProfilePage');
Route::post('/profile/updateProfile', [UserController::class, 'editProfile'])->name('profile.updateProfile');
Route::post('/profile/verifyPassword', [UserController::class, 'verifyPassword'])->name('profile.verifyPassword');
Route::post('/profile/updatePassword', [UserController::class, 'updatePassword'])->name('profile.updatePassword');

Route::post('/update_fitness_goal', [UserController::class, 'updateFitnessGoal'])->name('update_fitness_goal');
Route::get('/gymplanning', [UserController::class, 'showGymPlanner'])->name('gymplanning')->middleware(\App\Http\Middleware\PlanningMiddleWare::class);
Route::get('/mealplanning', [UserController::class, 'showMealPlanner'])->name('mealplanning')->middleware(\App\Http\Middleware\PlanningMiddleWare::class);
Route::post('/update_meal_preference', [UserController::class, 'updateMealPreference'])->name('update_meal_preference');
Route::get('/onlinecoaching', [UserController::class, 'searchVideos'])->name('onlinecoaching');

// Public routes to handle input
Route::get('/prompt-user-data', [UserController::class, 'showForm'])->name('prompt_user_data');
Route::post('/prompt-user-data', [UserController::class, 'handleForm'])->name('handle_user_data');


