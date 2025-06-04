<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('login',[AuthController::class, 'login'])->name('login');
Route::get('register',[AuthController::class, 'register'])->name('register');

Route::post('signin-with-google',[AuthController::class,'SignInWithGoogle'])->name('signin.with.google');

Route::post('sign-up',[AuthController::class, 'signUp'])->name('sign.up');

Route::get('dashboard', [AuthController::class,'dashboard'])->name('dashboard');