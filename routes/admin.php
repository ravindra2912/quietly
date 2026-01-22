<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\DashboarController;
use App\Http\Controllers\Admin\LagelPagesController;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\PlanPurchaseController;
use App\Http\Controllers\Admin\ContactUsController;

Route::name('admin.')->group(function () {
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
            Route::get('setting/ads', 'ads')->name('setting.ads');
            Route::post('setting/ads', 'adsUpdate')->name('setting.ads.update');
        });

        Route::resource('blog', BlogController::class);
        Route::resource('plan', \App\Http\Controllers\Admin\PlanController::class);
        Route::resource('plan-purchase', PlanPurchaseController::class);
        Route::get('plan-purchase-search-users', [PlanPurchaseController::class, 'searchUsers'])->name('plan-purchase.search-users');
        Route::post('contact-us/update-status', [ContactUsController::class, 'updateStatus'])->name('contact-us.update-status');
        Route::resource('contact-us', ContactUsController::class)->only(['index', 'destroy', 'show']);
        // Summernote image upload route for admin blog
        Route::post('blog/upload-image', [BlogController::class, 'uploadImage'])->name('blog.uploadImage');



        Route::controller(AuthController::class)->group(function () {
            Route::get('logout', 'destroy')->name('logout');
        });
    });
});
