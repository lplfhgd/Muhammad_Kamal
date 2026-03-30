<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentApiController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/student-login-test', [StudentApiController::class, 'login']);
