<?php

use App\Helpers\RolesEnum;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExpressionController;
use App\Http\Controllers\Api\InstallationController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PendingExpressionController;
use App\Http\Controllers\Api\PendingWordController;
use App\Http\Controllers\Api\ReleaseController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\StatsController;
use App\Http\Controllers\Api\SuggestionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WordController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
    Route::group(['middleware' => ['role:' . RolesEnum::ADMIN_ROLE->value, 'cors',]], function () {
        Route::get('/users', [UserController::class, 'getUsers']);
        Route::get('/user', [UserController::class, 'getUser']);
        Route::post('/create-user', [UserController::class, 'create']);
        Route::get('/role', [RoleController::class, 'getRoles']);
        Route::post('/role', [RoleController::class, 'store']);
        Route::put('/role/{id}', [RoleController::class, 'update']);
        Route::put('/role/{id}/permissions', [RoleController::class, 'setPermissions']);
        Route::get('/permissions', [RoleController::class, 'getPermissions']);
        Route::post('/release', [ReleaseController::class, 'store']);
        Route::get('/setup-db', [WordController::class, 'import']);
        Route::delete('/delete-word/{id}', [WordController::class, 'destroy']);
        Route::get('/payments', [PaymentController::class, 'index']);
        Route::get('/clear-payments', [PaymentController::class, 'clear']);
        Route::get('/stats', [StatsController::class, 'index']);
    });


    Route::post('/create-word', [WordController::class, 'create']);
    Route::post('/create-expression', [ExpressionController::class, 'create']);
    Route::get('/get-words', [WordController::class, 'getWords']);

    Route::get('/suggestions', [SuggestionController::class, 'getSuggestions']);
    Route::post('/users/set-password', [AuthController::class, 'setPassword']);

    Route::get('/pending-words', [WordController::class, 'fetchPendingWords']);

    Route::post('/pending-words/create', [PendingWordController::class, 'create']);
    Route::get('/pending-words/{id}', [PendingWordController::class, 'show']);
    Route::get('/count-pending-words', [PendingWordController::class, 'countPendingWords']);
    Route::put('/pending-words/{id}', [PendingWordController::class, 'update']);

    Route::post('/pending-expressions/create', [PendingExpressionController::class, 'create']);
    Route::get('/pending-expressions/{id}', [PendingExpressionController::class, 'show']);
    Route::get('/count-pending-expressions', [PendingExpressionController::class, 'countPendingExpressions']);
    Route::put('/pending-expressions/{id}', [PendingExpressionController::class, 'update']);
});


Route::post('/users/installation', [InstallationController::class, 'store']);
Route::get('/users/checkDeviceSubscription/{id}', [InstallationController::class, 'checkDeviceSubscription']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/words', [WordController::class, 'fetch']);
Route::get('/release', [ReleaseController::class, 'getReleases']);
Route::post('/suggestions', [SuggestionController::class, 'store']);
Route::get('/suggestions/{id}', [SuggestionController::class, 'getSuggestionByDevice']);


Route::post('/create-payment', [PaymentController::class, 'store'])->middleware('cors');
Route::post('/confirm-payment', [PaymentController::class, 'confirmMoneroo']);
