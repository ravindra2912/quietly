<?php

use App\Http\Middleware\Admin;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\CommonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\BusinessController;
use App\Http\Controllers\Admin\DashboarController;
use App\Http\Controllers\Admin\LagelPagesController;
use App\Http\Controllers\Admin\LocationMasterController;
use App\Http\Controllers\Admin\BusinessCategoryController;
use App\Http\Controllers\Admin\RouteTripController;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\Admin\VihicleController;
use App\Http\Controllers\front\TripController;

Route::name('admin.')->group(function () {
    // Route::controller(CommonController::class)->group(function () {
    //     Route::post('getCities', 'getCities')->name('getCities');
    //     Route::post('get-citie-areas', 'getCitieArea')->name('getCitieArea');
    // });
    Route::middleware('web', 'guest')->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::get('login', 'index')->name('login');
            Route::post('login', 'store')->name('login');
        });
    });



    Route::middleware(['web', 'admin'])->group(function () {
        Route::controller(DashboarController::class)->group(function () {
            Route::get('dashboard', 'index')->name('dashboard');
        });

        Route::resource('user', UsersController::class);

        Route::resource('faq', FaqController::class);
        Route::resource('seo', SeoController::class);
        Route::controller(LagelPagesController::class)->group(function () {
            Route::get('lagel-pages', 'index')->name('lagel-pages');
            Route::get('lagel-pages/{id}', 'edit')->name('lagel-pages.edit');
            Route::post('lagel-pages/{id}', 'update')->name('lagel-pages.update');
        });

        Route::controller(SettingController::class)->group(function () {
            Route::get('setting/profile', 'profile')->name('setting.profile');
            Route::post('setting/profile/{id}', 'profileUpdate')->name('setting.profile.update');
        });



        Route::controller(AuthController::class)->group(function () {
            Route::get('logout', 'destroy')->name('logout');
        });
    });
});
