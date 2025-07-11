<?php

use App\Http\Controllers\Front\SupportController;
use Illuminate\Support\Facades\Route;


Route::redirect('/', '/support')->name('home');


Route::get('/support', [SupportController::class, 'index']);
Route::get('/support?action=direct', [SupportController::class, 'index'])->name('support.direct');
Route::get('/support/thanks', [SupportController::class, 'thanks'])->name('support.thanks');

