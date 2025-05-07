<?php

use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::domain('dico.iamyourclounon.bj')->group(function () {
    Scramble::registerUiRoute('docs');
    Scramble::registerJsonSpecificationRoute('api.json');
});
