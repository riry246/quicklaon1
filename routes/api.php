<?php

use App\Http\Controllers\Api\IllionController;
use App\Http\Controllers\Api\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BankController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\LoanQuestionController;
use App\Http\Controllers\Api\LoanReasonController;
use App\Http\Controllers\Api\LoanTermController;
use App\Http\Controllers\Api\BasiqController;
use App\Http\Controllers\Api\RapididController;
use App\Http\Controllers\Api\CustomerDashboardController;
use App\Http\Controllers\Api\RequestController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/reset-password', [AuthController::class, 'resetPassword'])->name('resetPassword');
Route::post('/mail/reset-password', [AuthController::class, 'sendResetPassword'])->name('sendResetPassword');

Route::post('/sendotp', [ApplicationController::class, 'sendSMS'])->name('application.sms');
Route::post('/verifyotp', [ApplicationController::class, 'submitOTP'])->name('application.verifyOTP');

/* will kept inside auth later*/
Route::post('/auth/getapplication', [ApplicationController::class, 'getApplicationInfo'])->name('application.getApplicationInfo');
Route::post('/auth/store', [ApplicationController::class, 'storeApplicationData'])->name('application.store');
Route::post('/auth/updateloan', [ApplicationController::class, 'updateAmountDuration'])->name('application.updateloan');

//Bank
Route::get('/auth/bank', [BankController::class, 'index'])->name('bank.index');
Route::post('/auth/bank', [BankController::class, 'store'])->name('bank.store');

//Loan
Route::get('/auth/loan-question', [LoanQuestionController::class, 'index'])->name('loanQuestion.index');
Route::get('/auth/loan-reason', [LoanReasonController::class, 'index'])->name('loanReason.index');
Route::get('/auth/loan-term', [LoanTermController::class, 'index'])->name('loanTerm.index');
Route::get('/auth/loan-term/{slug}', [LoanTermController::class, 'index'])->name('loanTerm.index.1');

//verify bank
Route::post('/auth/bankverify', [BasiqController::class, 'authenticate'])->name('bank.verify');


//verify ID
Route::post('/auth/idVerify', [RapididController::class, 'verify'])->name('id.verify');

//verify Email
Route::post('/auth/sendLink', [RequestController::class, 'sendEmailVerification'])->name('email.verifylink');
Route::post('/auth/uploaddoc', [RequestController::class, 'documentUpload'])->name('doc.upload');


//Referral
Route::post('/auth/referral', [RequestController::class, 'referral'])->name('customer.referral');
Route::post('/auth/referral-verify', [RequestController::class, 'validateReferalCode'])->name('customer.referralverify');

//Customer Dashboard
Route::post('/auth/customer/getapplication', [CustomerDashboardController::class, 'getApplication'])->name('customer.application');
Route::post('/auth/customer/addPayment', [ApplicationController::class, 'addPaymentInfo'])->name('customer.payment');

//Notification
Route::post('/auth/customer/notification', [RequestController::class, 'notification'])->name('customer.notification');
Route::post('/auth/customer/notification/read', [RequestController::class, 'markReadnotification'])->name('customer.notification.read');

//FCM
Route::post('/auth/customer/fcmtoken', [RequestController::class, 'storeFCM'])->name('customer.fcm');
Route::post('/auth/customer/sendnotification', [RequestController::class, 'sendNotification'])->name('customer.sendNotification');

//Pre-approved
Route::post('/auth/customer/preapproved', [RequestController::class, 'preApproval'])->name('customer.preApproval');

//Update Profile
Route::post('/auth/update-profile', [CustomerDashboardController::class, 'updateProfile'])->name('update.profile');

//logout
Route::post('/auth/logout/{id}', [CustomerDashboardController::class, 'logout'])->name('user.logout');

//contract
Route::post('/auth/contract', [RequestController::class, 'contractSigning'])->name('contract.sign');
Route::post('/auth/contract/viewed', [RequestController::class, 'contractSigningViewed'])->name('contract.view');

//admin contract
Route::post('/auth/admin/contract', [RequestController::class, 'contractAdminSigning'])->name('contract.admin.sign');
Route::post('/auth/admin/contract/viewed', [RequestController::class, 'contractAdminSigningViewed'])->name('contract.admin.view');



Route::post('/auth/iframe', [ApplicationController::class, 'getIllionIframe'])->name('illion.iframe');
Route::post('/auth/getillionAccount', [IllionController::class, 'getCustomerdata'])->name('illion.customer.account');
Route::post('/auth/setPrimaryAccount', [IllionController::class, 'updatePrimaryAccount'])->name('illion.customer.update');


//Payment
Route::get('/auth/statement/{id}/pay', [PaymentController::class, 'payInstalment'])->name('loan.payment');

Route::get('/auth/statement/{id}/reschedule', [PaymentController::class, 'reschedulePayment'])->name('loan.reschedule');
Route::get('/auth/settlement/{id}/pay', [PaymentController::class, 'payall'])->name('loan.settlment');


Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::group(['middleware' => 'auth:sanctum'], function () {

    });
});