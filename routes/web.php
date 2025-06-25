<?php

use App\Http\Controllers\Front\SupportController;
use Illuminate\Support\Facades\Route;


Route::redirect('/', '/support');


Route::get('/support', [SupportController::class, 'index']);
Route::get('/support/thanks', [SupportController::class, 'thanks'])->name('support.thanks');

