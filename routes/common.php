<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CommonApiController;


Route::get('/check-login', [CommonApiController::class, 'checkLogin']);
Route::get('/get-legal-page/{page}', [CommonApiController::class, 'getLegalPage']);
Route::get('/other-apps', [CommonApiController::class, 'otherApps']);
