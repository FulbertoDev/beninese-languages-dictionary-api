<?php

use App\Http\Controllers\Front\SupportController;
use Illuminate\Support\Facades\Route;

$encrypted = "uQijhmMq6Ycs3th-b5YMDXCmoq9qg_nr-S9HlrVa3JJWecERjkP5RbbF6KXSQnn5Qg5Ex81WbZuJH56iwTYy_rlvg-3FT8U6XmdwVGEj6QXhfbhKiAr2Zt869GDGIgMWC8HG4PkROd6katkb_IL6TyhuDUZAyRukDs4cegmCtYX6ZlHWVB3Mzb_c0oBCQXv4D85H94IXn2J4WqDD6EwFfA";

/*
Route::get('/', function () {
})*/


Route::redirect('/', '/support?token=' . $encrypted);


Route::get('/support', [SupportController::class, 'index']);
Route::get('/support/thanks', [SupportController::class, 'thanks'])->name('support.thanks');

