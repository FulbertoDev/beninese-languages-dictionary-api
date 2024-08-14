<?php

use App\Helpers\RolesEnum;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InstallationController;
use App\Http\Controllers\Api\ReleaseController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SuggestionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WordController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
    Route::group([
        'middleware' => ['role:' . RolesEnum::ADMIN_ROLE->value,]
    ], function () {
        Route::get('/users', [UserController::class, 'getUsers']);
        Route::post('/create-user', [UserController::class, 'create']);
        Route::get('/role', [RoleController::class, 'getRoles']);
        Route::post('/role', [RoleController::class, 'store']);
        Route::put('/role/{id}', [RoleController::class, 'update']);
        Route::put('/role/{id}/permissions', [RoleController::class, 'setPermissions']);
        Route::get('/permissions', [RoleController::class, 'getPermissions']);
    });
});

Route::post('/users/installation', [InstallationController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/words', [WordController::class, 'fetch']);
Route::get('/pending-words', [WordController::class, 'fetchPendingWords']);
Route::post('/release', [ReleaseController::class, 'store']);
Route::get('/release', [ReleaseController::class, 'getReleases']);
Route::post('/import', [WordController::class, 'import']);
Route::post('/suggestions', [SuggestionController::class, 'store']);
Route::get('/suggestions', [SuggestionController::class, 'getSuggestions']);
Route::get('/suggestions/{id}', [SuggestionController::class, 'getSuggestionByDevice']);
