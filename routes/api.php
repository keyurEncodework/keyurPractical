<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function(){
    Route::apiResource('users',UserController::class);
});