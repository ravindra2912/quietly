<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Version1\AuthController;
use App\Http\Controllers\Api\Version1\QueryController;
use App\Http\Controllers\Api\Version1\CommonController;
use App\Http\Controllers\Api\Version1\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::group(['prefix' => '/version-1'], function () {
    // Route::get('login', function () {
    //     dd('a');
    // });
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/registration', [AuthController::class, 'registration']);


    Route::get('/get-legal-page/{page}', [CommonController::class, 'getLegalPage']);


    Route::group(['middleware' => ['auth:api']], function () {

        Route::controller(UserController::class)->group(function () {
            Route::post('/update-user-profile', 'UpdateProfile');
            Route::delete('/remove-account', 'removeAccount');
        });

        Route::post('/logout', [AuthController::class, 'logout']);
    });
});
