<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Ajax\AjaxAuthController;
use App\Http\Controllers\Ajax\AjaxAdminController;
use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Ajax\AjaxAccountController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/login', function() { return redirect('/'); } )->name('login');
Route::get('/register', function() { return redirect('/'); } )->name('register');
Route::get('/username', function() { return view('auth.create_username'); });

Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth');

// Login with Google
Route::group([
    'prefix' => 'auth'
    ], function () {
    Route::get('/google', [AuthController::class, 'google_login'])->name('google.login');
    Route::get('/google/callback', [AuthController::class, 'google_callback'])->name('google.callback');
});
Route::post('auth/google/register', [AuthController::class, 'google_register']);

// Admin Panel
Route::group([
    'prefix' => 'admin'
    ], function () {
    Route::get('/users', [AdminController::class, 'users_controller'])->name('admin.users');
});

// Account
Route::group([
    'prefix' => 'account'
    ], function () {
    Route::get('/profile', [AccountController::class, 'profile'])->name('account.profile');
});

// Ajax
Route::group([
    'prefix' => 'ajax'
    ], function () {
    Route::post('/account', [AjaxAccountController::class, 'action']);
    Route::post('/auth', [AjaxAuthController::class, 'action']);
    Route::post('/admin', [AjaxAdminController::class, 'action']);
});

// Route::get('/test', function() {
//     return view('mails.otp');
// });


