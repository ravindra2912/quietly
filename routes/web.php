<?php

use App\Http\Controllers\front\BookingController;
use App\Http\Controllers\front\DashboardController;
use App\Http\Controllers\front\TripController;
use App\Http\Controllers\front\BlogController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//sitemap
Route::get('sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', function () {
    $lines = [
        'User-agent: *',
        'Disallow: /admin/',        // Block admin pages
        'Disallow: /login',         // Block login page
        'Disallow: /register',      // Block register page
        'Allow: /',                 // Allow everything else
        'Sitemap: ' . url('/sitemap.xml'), // Link to your sitemap
    ];

    return response(implode("\n", $lines), 200)
        ->header('Content-Type', 'text/plain');
});

Route::controller(DashboardController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::post('/store-contact-us', 'storeContactUs')->name('storeContactUs');
});

Route::get('/privacy-policy', function () {
    return view('front.privacy-policy');
})->name('PrivacyPolicy');

Route::get('/terms-conditions', function () {
    return view('front.terms-conditions');
})->name('TermsConditions');

Route::get('/cancellation-returns-policy', function () {
    return view('front.cancellation-returns-policy');
})->name('CancellationReturnsPolicy');

Route::get('/about', function () {
    return view('front.about-us');
})->name('aboutUs');

Route::get('/contact', function () {
    return view('front.contact-us');
})->name('ContactUs');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    Route::resource('blog', AdminBlogController::class);
    Route::post('contact-us/update-status', [\App\Http\Controllers\Admin\ContactUsController::class, 'updateStatus'])->name('contact-us.update-status');
    Route::resource('contact-us', \App\Http\Controllers\Admin\ContactUsController::class)->only(['index', 'destroy', 'show']);
    // Summernote image upload route for admin blog
    Route::post('blog/upload-image', [AdminBlogController::class, 'uploadImage'])->name('blog.uploadImage');
});
