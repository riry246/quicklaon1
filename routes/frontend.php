<?php

use App\Http\Controllers\App\AuthController;
use App\Http\Controllers\App\CustomerDashboardController;
use App\Http\Controllers\App\ApplicationController;
use App\Http\Controllers\App\CustomerNotificationController;



//Route::group(['domain' => 'app.cashfaster.com.au'], function () {
   
Route::get('/', [AuthController::class, 'index'])->name('home'); 
Route::get('/apply', [AuthController::class, 'index'])->name('apply'); 
Route::get('/reset-password', [AuthController::class, 'resetPassword'])->name('resetPassword');
Route::post('/mail/reset-password', [AuthController::class, 'sendResetPassword'])->name('sendResetPassword');

//Customer Dashboard
Route::prefix('customer')->middleware(['web'])->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');
    Route::get('/billing', [CustomerDashboardController::class, 'billing'])->name('customer.billing');
    Route::get('/settings', [CustomerDashboardController::class, 'settings'])->name('customer.settings');
    Route::get('/logout', [CustomerDashboardController::class, 'logout'])->name('customer.logout');
});


Route::post('/', [AuthController::class, 'verify'])->name('user.login');


Route::post('/customer/dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');
Route::get('/customer/autologin/{id}', [CustomerDashboardController::class, 'autoLogin'])->name('customer.autologin');


Route::get('/application', [ApplicationController::class, 'index'])->name('application');
Route::get('/application/{step}', [ApplicationController::class, 'applicationForm'])->name('application.steps');

Route::post('/sendotp', [ApplicationController::class, 'sendSMS'])->name('application.sms');
Route::post('/verifyotp', [ApplicationController::class, 'submitOTP'])->name('application.verifyOTP');
Route::post('/store', [ApplicationController::class, 'storeApplicationData'])->name('application.store');
Route::get('/verify/email/{token}', [ApplicationController::class, 'verifyemail'])->name('email.verify');


Route::get('customer/mark-as-read', [CustomerNotificationController::class, 'markAsRead'])->name('app.mark-as-read');
Route::get('customer/notification', [CustomerNotificationController::class, 'index'])->name('app.notiifcaiton');

//Lead Market
Route::get('customer/leadmarket/{token}', [CustomerDashboardController::class, 'leadmarket'])->name('app.leadmarket');
Route::get('lead/', [CustomerDashboardController::class, 'leadmarketBuy'])->name('app.leadmarket.buy');




//});