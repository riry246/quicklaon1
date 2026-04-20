<?php

use App\Http\Controllers\Admin\Illion\IllionController;
use App\Http\Controllers\Admin\Monoova\WebHookController;
use App\Http\Controllers\Admin\SendEmailController;
use App\Http\Controllers\Admin\SMSController;
use App\Http\Controllers\App\CustomerDashboardController;
use App\Http\Controllers\LeadWebhookController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/clear-cache', function () {
    // Clear the application cache
    Artisan::call('cache:clear');

    // Clear the application view cache
    Artisan::call('view:clear');

    // Clear the application config cache
    Artisan::call('config:clear');

    // Clear the cache
    Cache::flush();

    // Clear the storage (public disk)
    Artisan::call('storage:link');

    return "Cache and storage cleared.";
})->name('clear-cache');


Route::group(['middleware' => 'web'], function () {
    require base_path('routes/admin.php');
});

// Include frontend routes
Route::group(['middleware' => 'web'], function () {
    require base_path('routes/frontend.php');
});



Route::post('/lead-webhook', [LeadWebhookController::class, 'handleLeadPurchase']);
Route::get('/lead/apply', [LeadWebhookController::class, 'LeadPurchase']);
Route::get('/lead/timeout', [LeadWebhookController::class, 'timeoutNotification']);


//Moonova webhook
Route::get('/subscribe', [WebHookController::class, 'subscribe']);
Route::post('/npp-receive-payment', [WebHookController::class, 'NPPReceivePayment']);
Route::get('/pay-to-npp-receive-payment', [WebHookController::class, 'NPPReceivePayment']);


//Auto login
Route::get('/users/login/{id}', [CustomerDashboardController::class, 'autoLoginCustomer'])->name('customer.force.login');


//temp
Route::get('/sendappemail', [SendEmailController::class, 'index'])->name('applogy.email');

Route::get('/illion_test', [IllionController::class, 'handleWebhook'])->name('illion.iframeTest');    
Route::post('/illion_webhook', [IllionController::class, 'handleWebhook'])->name('illion.webhook');      
Route::post('/illion_async_report', [IllionController::class, 'asyncReport'])->name('illion.async');      




Route::post('/sms/webhook', [SMSController::class, 'handleIncomingSMS']);


// 404 Error Page
Route::get('/404',function () {
    return view('errors.404');
})->name('error.404');

