<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WordController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/import', [WordController::class, 'import']);
    Route::get('/users', [UserController::class, 'getUsers']);
    Route::post('/create-user', [UserController::class, 'create']);
    Route::put('/set-user-abilities/{id}', [UserController::class, 'setUserAbilities']);
});

Route::post('/login', [AuthController::class, 'login']);
