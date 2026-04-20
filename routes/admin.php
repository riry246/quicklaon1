<?php

use App\Http\Controllers\Admin\ActivityScoreController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\BankstatementController;
use App\Http\Controllers\Admin\ContractController;
use App\Http\Controllers\Admin\ContractSignController;
use App\Http\Controllers\Admin\CsvImportController;
use App\Http\Controllers\Admin\EmailMarketing;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\ExperianController;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\Illion\IllionController;
use App\Http\Controllers\Admin\Illion\IllionServiceAbilityController;
use App\Http\Controllers\Admin\LeadMarketBoughtController;
use App\Http\Controllers\Admin\LeadMarketController;
use App\Http\Controllers\Admin\LoanApplication\BadDebtController;
use App\Http\Controllers\Admin\LoanApplication\InterestController;
use App\Http\Controllers\Admin\LoanStatementController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin\ServiceAbilityController;
use App\Http\Controllers\Admin\SMSController;
use App\Http\Controllers\Admin\SMSTemplateController;
use App\Http\Controllers\Admin\TransactionController;

use App\Http\Controllers\Admin\LoanApplicationController;
use App\Http\Controllers\Admin\LoanCaseController;
use App\Http\Controllers\Admin\LoanQuestionController;
use App\Http\Controllers\Admin\LoanReasonController;
use App\Http\Controllers\Admin\LoanTermController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;

use App\Http\Controllers\Admin\WalletController;
use App\Http\Controllers\App\AuthController;
use App\Http\Controllers\Admin\Illion\CreditCheckController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentTypeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\ModuleActionController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\UserAttrSetController;
use App\Http\Controllers\App\CustomerDashboardController;
use App\Http\Controllers\Admin\RequestInformationController;

//Route::group(['domain' => 'admin.cashfaster.com.au'], function () {

// Route::get('/', function () {
//  return redirect('admin/login');
// })->name('login');


Route::middleware(['2fa'])->group(function () {
    Route::post('/2fa', function () {
        return redirect(route('dashboard'));
    })->name('2fa');
});


Route::prefix('admin')->middleware(['preventCustomerFromAdmin'])->group(function () {
    //Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/metric', [DashboardController::class, 'getMetrics'])->name('dashboard.metrics');
    Route::post('/showBadDebt', [DashboardController::class, 'showBadDebt'])->name('dashboard.showBadDebt');

    //Users
    Route::get('/users', [UserController::class, 'index'])->middleware('permission:View User Management')->name('user.index');

    Route::get('/users/create', [UserController::class, 'create'])->middleware('permission:Create User Management')->name('user.create');
    Route::post('/users', [UserController::class, 'store'])->middleware('permission:Create User Management')->name('user.store');
    Route::get('/users/{user_id}/edit', [UserController::class, 'edit'])->middleware('permission:Update User Management')->name('user.edit');
    Route::post('/users/{user_id}', [UserController::class, 'update'])->middleware('permission:Update User Management')->name('user.update');
    Route::get('/users/{user_id}', [UserController::class, 'destroy'])->middleware('permission:Delete User Management')->name('user.delete');

    //customer
    Route::get('/users/customer/list', [UserController::class, 'customer'])->middleware('permission:View User Management')->name('user.customer');
    Route::get('/users/customer/{user_id}/edit', [UserController::class, 'edit'])->middleware('permission:Update User Management')->name('customer.edit');
    Route::get('/users/customer/{user_id}', [UserController::class, 'destroy'])->middleware('permission:Delete User Management')->name('customer.delete');
    Route::get('/users/application/{user_id}', [LoanApplicationController::class, 'userWiseLoanApplicaiton'])->middleware('permission:Delete User Management')->name('customer.view');


    //Groups
    Route::get('/groups', [GroupController::class, 'index'])->middleware('permission:View User Groups')->name('group.index');
    Route::get('/groups/create', [GroupController::class, 'create'])->middleware('permission:Create User Groups')->name('group.create');
    Route::post('/groups', [GroupController::class, 'store'])->middleware('permission:Create User Groups')->name('group.store');
    Route::get('/groups/{group_id}/edit', [GroupController::class, 'edit'])->middleware('permission:Update User Groups')->name('group.edit');
    Route::post('/groups/{group_id}', [GroupController::class, 'update'])->middleware('permission:Update User Groups')->name('group.update');
    Route::get('/groups/{group_id}', [GroupController::class, 'destroy'])->middleware('permission:Delete User Groups')->name('group.delete');

    //Module
    Route::get('/modules', [ModuleController::class, 'index'])->middleware('permission:View Modules')->name('module.index');
    Route::get('/modules/create', [ModuleController::class, 'create'])->middleware('permission:Create Modules')->name('module.create');
    Route::post('/modules', [ModuleController::class, 'store'])->middleware('permission:Create Modules')->name('module.store');
    Route::get('/modules/{module_id}/edit', [ModuleController::class, 'edit'])->middleware('permission:Update Modules')->name('module.edit');
    Route::post('/modules/{module_id}', [ModuleController::class, 'update'])->middleware('permission:Update Modules')->name('module.update');
    Route::get('/modules/{module_id}', [ModuleController::class, 'destroy'])->middleware('permission:Delete Modules')->name('module.delete');

    //Module Action
    Route::get('/module_actions', [ModuleActionController::class, 'index'])->middleware('permission:View Module Action')->name('module_action.index');
    Route::get('/module_actions/create', [ModuleActionController::class, 'create'])->middleware('permission:Create Module Action')->name('module_action.create');
    Route::post('/module_actions', [ModuleActionController::class, 'store'])->middleware('permission:Create Module Action')->name('module_action.store');
    Route::get('/module_actions/{module_id}/edit', [ModuleActionController::class, 'edit'])->middleware('permission:Update Module Action')->name('module_action.edit');
    Route::post('/module_actions/{module_id}', [ModuleActionController::class, 'update'])->middleware('permission:Update Module Action')->name('module_action.update');
    Route::get('/module_actions/{module_id}', [ModuleActionController::class, 'destroy'])->middleware('permission:Delete Module Action')->name('module_action.delete');

    //Bank
    Route::get('/bank', [BankController::class, 'index'])->name('bank.index');
    Route::get('/bank/create', [BankController::class, 'create'])->name('bank.create');
    Route::post('/bank', [BankController::class, 'store'])->name('bank.store');
    Route::get('/bank/{user_id}/edit', [BankController::class, 'edit'])->name('bank.edit');
    Route::post('/bank/{user_id}', [BankController::class, 'update'])->name('bank.update');
    Route::get('/bank/list', [BankController::class, 'update_bak'])->name('bank.list');
    Route::get('/bank/{user_id}', [BankController::class, 'destroy'])->name('bank.delete');
    Route::post('/bank/change/primaryaccount', [BankController::class, 'changePrimaryAccount'])->name('bank.change.primary');


    //Reason
    Route::get('/reason', [LoanReasonController::class, 'index'])->name('reason.index');
    Route::get('/reason/create', [LoanReasonController::class, 'create'])->name('reason.create');
    Route::post('/reason', [LoanReasonController::class, 'store'])->name('reason.store');
    Route::get('/reason/{id}/edit', [LoanReasonController::class, 'edit'])->name('reason.edit');
    Route::post('/reason/{id}', [LoanReasonController::class, 'update'])->name('reason.update');
    Route::get('/reason/{id}', [LoanReasonController::class, 'destroy'])->name('reason.delete');

    //Term
    Route::get('/term', [LoanTermController::class, 'index'])->name('term.index');
    Route::get('/term/create', [LoanTermController::class, 'create'])->name('term.create');
    Route::post('/term', [LoanTermController::class, 'store'])->name('term.store');
    Route::get('/term/{id}/edit', [LoanTermController::class, 'edit'])->name('term.edit');
    Route::post('/term/{id}', [LoanTermController::class, 'update'])->name('term.update');
    Route::get('/term/{id}', [LoanTermController::class, 'destroy'])->name('term.delete');

    //Question
    Route::get('/question', [LoanQuestionController::class, 'index'])->name('question.index');
    Route::get('/question/create', [LoanQuestionController::class, 'create'])->name('question.create');
    Route::post('/question', [LoanQuestionController::class, 'store'])->name('question.store');
    Route::get('/question/{id}/edit', [LoanQuestionController::class, 'edit'])->name('question.edit');
    Route::post('/question/{id}', [LoanQuestionController::class, 'update'])->name('question.update');
    Route::get('/question/{id}', [LoanQuestionController::class, 'destroy'])->name('question.delete');

    //Email Template
    Route::get('/email-template', [EmailTemplateController::class, 'index'])->name('email.template.index');
    Route::get('/email-template/create', [EmailTemplateController::class, 'create'])->name('email.template.create');
    Route::post('/email-template', [EmailTemplateController::class, 'store'])->name('email.template.store');
    Route::get('/email-template/{id}/edit', [EmailTemplateController::class, 'edit'])->name('email.template.edit');
    Route::post('/email-template/{id}', [EmailTemplateController::class, 'update'])->name('email.template.update');
    Route::get('/email-template/{id}', [EmailTemplateController::class, 'destroy'])->name('email.template.delete');

    //SMS Template
    Route::get('/sms-template', [SMSTemplateController::class, 'index'])->name('sms.template.index');
    Route::get('/sms-template/create', [SMSTemplateController::class, 'create'])->name('sms.template.create');
    Route::post('/sms-template', [SMSTemplateController::class, 'store'])->name('sms.template.store');
    Route::get('/sms-template/{id}/edit', [SMSTemplateController::class, 'edit'])->name('sms.template.edit');
    Route::post('/sms-template/{id}', [SMSTemplateController::class, 'update'])->name('sms.template.update');
    Route::get('/sms-template/{id}', [SMSTemplateController::class, 'destroy'])->name('sms.template.delete');


    //Loan Application
    Route::get('/loan/{stat}', [LoanApplicationController::class, 'index'])->name('loan.list');
    Route::get('/loan-details', [LoanApplicationController::class, 'loanDetail'])->name('loan.detail');
    Route::get('/loan', [LoanApplicationController::class, 'index'])->name('loan.index');
    Route::get('/loan/sss', [LoanApplicationController::class, 'index'])->name('loan.create');
    Route::get('/loan/{id}/show', [LoanApplicationController::class, 'show'])->name('loan.view');
    Route::get('/loan/{id}/edit', [LoanApplicationController::class, 'edit'])->name('loan.edit');
    Route::post('/loan/{id}', [LoanApplicationController::class, 'update'])->name('loan.update');
    Route::post('/loan/{id}/assign', [LoanApplicationController::class, 'assignTo'])->name('loan.assign');



    //Loan Statement
    Route::get('/loanStatement/{userid}/{id}', [LoanStatementController::class, 'index'])->name('loan.statement');
    Route::post('/loanStatement/reschedule/{userid}/{id}', [LoanStatementController::class, 'reschedulePayment'])->name('loan.reschedule');
    Route::post('/loanStatement/update/statement/{userid}/{id}', [LoanStatementController::class, 'updateStatement'])->name('loan.update.statement');
    Route::post('/loanStatement/update/statement/partial/{userid}/{id}', [LoanStatementController::class, 'partialUpdateStatement'])->name('loan.update.statement.partial');
    Route::post('/loanStatement/rescheduleWhole/{id}', [LoanStatementController::class, 'reschdeuleWhole'])->name('loan.reschdeuleWhole');
    Route::post('/loanStatement/add/{id}', [LoanStatementController::class, 'addNewPayment'])->name('loan.addNewPayment');

    //Bankstatement
    Route::get('/bankStatement/{id}', [BankstatementController::class, 'index'])->name('bankStatement');
    Route::get('/bankStatement/viewAffordabilityStatement/{id}', [BankstatementController::class, 'viewAffordabilityStatement'])->name('bankStatement.affordability');
    Route::get('/bankStatement/update/{id}', [BankstatementController::class, 'updateBankStatement'])->name('bankStatement.update');
    Route::get('/bankStatement/affordability/{id}', [BankstatementController::class, 'updateAffordability'])->name('bankStatement.affordability.create');
    Route::get('/bankStatement/{id}/{accountID}', [BankstatementController::class, 'accountStatment'])->name('bankStatement.statement');


    Route::get('/viewConsumerStatement/{id}', [BankstatementController::class, 'viewReport'])->name('bankStatement.consumer');
    Route::get('/viewConsumerStatement/update/{id}', [BankstatementController::class, 'updateConsumerStatement'])->name('bankStatement.consumer.update');
    Route::post('/viewConsumerStatement/generate', [BankstatementController::class, 'generateConsumerStatement'])->name('bankStatement.consumer.generate');

    //Requests
    Route::post('/loan/{id}/request-document', [RequestInformationController::class, 'requestDocument'])->name('loan.requestDocument');
    Route::post('/loan/{id}/request-email', [RequestInformationController::class, 'emailVerification'])->name('loan.requestEmail');
    Route::post('/loan/{id}/request-mobile', [RequestInformationController::class, 'mobileVerification'])->name('loan.requestMobile');
    Route::post('/loan/{id}/request-id', [RequestInformationController::class, 'requestId'])->name('loan.requestId');
    Route::post('/loan/{id}/request-bank', [RequestInformationController::class, 'requestBank'])->name('loan.bankrequest');
    Route::get('/loan/{id}/reminder', [RequestInformationController::class, 'sendReminder'])->name('loan.reminder');


    //Case
    Route::post('/loan/case/{loan_application_id}', [LoanCaseController::class, 'create'])->name('loan.case');
    Route::get('/case/{id}', [LoanCaseController::class, 'view'])->name('case.view');
    Route::get('/case', [LoanCaseController::class, 'index'])->name('case.index');
    Route::get('/case/list/{status}', [LoanCaseController::class, 'index'])->name('case.list');
    Route::post('/case/update/{id}', [LoanCaseController::class, 'update'])->name('case.update');



    Route::post('/loan/{id}/approve', [PaymentController::class, 'approveLoan'])->name('loan.approved');
    Route::post('/loan/{id}/preapprove', [LoanApplicationController::class, 'preApproval'])->name('loan.pre.approved');
    Route::get('/authcode', [LoanApplicationController::class, 'sendAuthCode'])->name('loan.authcode');
    Route::post('/loan/{id}/decline', [LeadMarketController::class, 'declineLoan'])->name('loan.decline');
    Route::get('/loan/{id}', [LoanApplicationController::class, 'destroy'])->name('loan.delete');
    Route::get('/loan/{id}/change-status/{status}', [LoanApplicationController::class, 'changeLoanStatus'])->name('loan.status');
    Route::get('/loan-application/clear-view-status/{id}', [LoanApplicationController::class, 'clearViewStatus'])->name('loan-application.clear-view-status');

    Route::get('/loan/{id}/pay', [PaymentController::class, 'payInstalment'])->name('loan.payment');

    //Document Type
    Route::get('/document-types', [DocumentTypeController::class, 'index'])->name('document_type.index');
    Route::get('/document-types/create', [DocumentTypeController::class, 'create'])->name('document_type.create');
    Route::post('/document-types', [DocumentTypeController::class, 'store'])->name('document_type.store');
    Route::get('/document-types/{id}/edit', [DocumentTypeController::class, 'edit'])->name('document_type.edit');
    Route::post('/document-types/{id}', [DocumentTypeController::class, 'update'])->name('document_type.update');
    Route::get('/document-types/{id}', [DocumentTypeController::class, 'destroy'])->name('document_type.delete');

    //Settings
    Route::put('/setting/update/{slug}', [SettingController::class, 'update'])->name('setting.update');
    Route::post('/setting', [SettingController::class, 'view'])->name('setting.index');
    Route::get('/setting/{slug}', [SettingController::class, 'view'])->name('settings');


    //Notification
    Route::get('/mark-as-read', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
    Route::get('/notification', [NotificationController::class, 'index'])->name('notiifcaiton');

    //wheel
    Route::get('wheel-of-fortune', [GameController::class, 'index'])->name('wheel');

    //Credit check
    Route::get('/get-experian-token/{id}', [ExperianController::class, 'getToken'])->name('credit.score.generate');
    Route::post('/get-experian-token/update', [ExperianController::class, 'updateToken'])->name('credit.score.update');
    Route::get('/credit-score/{id}', [ExperianController::class, 'index'])->name('credit.score.latest');

    //
    Route::get('/createAccount/{id}', [PaymentController::class, 'dispatchAmount']);
    Route::get('/createPayid/{id}', [PaymentController::class, 'createPayID'])->name('payid.create');

    //Activity Score
    Route::get('/score', [ActivityScoreController::class, 'index'])->name('activity.score.index');
    Route::get('/score/create', [ActivityScoreController::class, 'create'])->name('activity.score.create');
    Route::post('/score', [ActivityScoreController::class, 'store'])->name('activity.score.store');
    Route::get('/score/{id}/edit', [ActivityScoreController::class, 'edit'])->name('activity.score.edit');
    Route::post('/score/{id}', [ActivityScoreController::class, 'update'])->name('activity.score.update');
    Route::get('/score/{id}', [ActivityScoreController::class, 'destroy'])->name('activity.score.delete');

    //Transaction
    Route::get('/transaction/{slug}', [TransactionController::class, 'index'])->name('transaction.index');
    Route::get('/transaction/principal/transfered', [TransactionController::class, 'principal'])->name('transaction.principal');
    Route::get('/transaction/user/{id}', [TransactionController::class, 'transactionByUserID'])->name('transaction.user.list');
    Route::get('/transaction', [TransactionController::class, 'test'])->name('transaction.redirect');
    Route::get('/transaction/view/{id}', [TransactionController::class, 'viewDetail'])->name('transaction.view');
    Route::get('/transaction/check/{id}', [TransactionController::class, 'updateTransactionStatus'])->name('transaction.check');

    //Panda doc Contract Sign
    //Route::post('/contract-sign/{id}', [ContractSignController::class, 'index'])->name('contract.create');
    //Route::get('/contract/status/{id}', [ContractSignController::class, 'checkStatus'])->name('contract.status');
    // Route::get('/contract/download/{id}', [ContractSignController::class, 'download'])->name('contract.download');


    //Cashfaster Contract
    Route::post('/contract-sign/{id}', [ContractController::class, 'index'])->name('contract.create');
    Route::get('/contract/status/{id}', [ContractController::class, 'checkStatus'])->name('contract.status');
    Route::get('/contract/download/{id}', [ContractController::class, 'download'])->name('contract.download');

    Route::get('/contract/{slug}', [CustomerDashboardController::class, 'contract'])->name('contract');



    //Lead Market sold
    Route::get('/leadmarket/sold', [LeadMarketController::class, 'index'])->name('leadmarket.index');
    Route::get('/leadmarket/view/{id}', [LeadMarketController::class, 'view'])->name('leadmarket.view');
    Route::get('/leadmarket/sell/{id}', [LeadMarketController::class, 'sellLead'])->name('leadmarket.sale');

    //Lead Market bought
    Route::get('/leadmarket/bought', [LeadMarketBoughtController::class, 'index'])->name('leadmarket.bought.index');
    Route::get('/leadmarket/bought/view/{id}', [LeadMarketBoughtController::class, 'view'])->name('leadmarket.bought.view');
    Route::get('/leadmarket/update/{id}', [LeadMarketBoughtController::class, 'leadUpdate'])->name('leadmarket.bought.leadUpdate');

    Route::get('/message', [MessageController::class, 'index'])->name('message');
    Route::post('/message/send/all', [MessageController::class, 'sendMessageAll'])->name('message.send.all');
    Route::post('/message/send', [MessageController::class, 'sendMessage'])->name('message.send');
    Route::get('/message/view/{id}', [MessageController::class, 'getIndividualMsg'])->name('message.view');
    Route::get('/message/loanapplication/view/{id}', [MessageController::class, 'getMessages'])->name('message.loan.view');

    //Import
    Route::get('/user/import', [CsvImportController::class, 'showForm'])->name('user.import');
    Route::post('/user/import/file', [CsvImportController::class, 'import'])->name('user.import.file');


    //Wallet
    Route::get('/wallet/send', [WalletController::class, 'send'])->name('wallet.send');
    Route::post('/wallet/process', [WalletController::class, 'process'])->name('wallet.process');
    Route::get('/wallet/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');

    //Search
    Route::post('/search', [SearchController::class, 'search'])->name('search');

    //Report
    Route::get('/report', [ReportController::class, 'index'])->name('report');
    Route::post('/report/submit', [ReportController::class, 'submit'])->name('report.submit');
    Route::get('/report/cohort', [ReportController::class, 'cohort'])->name('report.cohort');
    Route::post('/report/cohort/submit', [ReportController::class, 'cohortSubmit'])->name('report.cohort.submit');



    //2FA Authcode
    Route::get('/2faAuth', [LoanApplicationController::class, 'send2FAauthCode'])->name('loan.authcode.generator');

    //Anyltics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/manage/{slug}', [AnalyticsController::class, 'index'])->name('analytics');
    Route::post('/analytics/store', [AnalyticsController::class, 'store'])->name('analytics.store');
    Route::get('/analytics/delete/{slug}/{id}', [AnalyticsController::class, 'delete'])->name('analytics.delete');
    Route::post('/analytics/report/', [ServiceAbilityController::class, 'index'])->name('analytics.detail');
    Route::get('/analytics/report/view/{user_id}/{id}', [ServiceAbilityController::class, 'view'])->name('analytics.detail.view');

    //Bad Debt Controller
    Route::post('/markBadDebt', [BadDebtController::class, 'updateBadDebtStatus'])->name('update.badDebt');
    Route::get('/checkBadBebt', [BadDebtController::class, 'checkForBadDebt'])->name('check.badDebt');


    //Intrest Controller
    Route::get('/add_extra_interest', [InterestController::class, 'index'])->name('check.interest');
    Route::get('/interest_correction', [InterestController::class, 'correctInterest'])->name('correct.interest');

    //Illion 
    Route::get('/get_bank', [IllionController::class, 'getBankList'])->name('illion.Institutions');    
    Route::get('/get_customer_data/{userid}/{id}', [IllionController::class, 'getCustomerdata'])->name('illion.customer.data');    
    Route::get('/get_report/{id}', [IllionController::class, 'viewHtmlStatement'])->name('illion.customer.statement');    
    Route::get('/get_html/{id}', [IllionController::class, 'extractZipStatement'])->name('illion.customer.html');    
    Route::get('/getIllionCost', [IllionController::class, 'getIllionCost'])->name('illion.cost.check');
    Route::get('/credit-check/{id}', [CreditCheckController::class, 'doCreditCheck'])->name('illion.credit.check');

    //Illion ServiceAbility
    Route::get('/generate_service_ability/{id}', [IllionServiceAbilityController::class, 'index'])->name('illion.service.ability');    





    Route::get('/sms', [SMSController::class, 'index'])->name('sms');
    Route::post('/sms/reply', [SMSController::class, 'sendReply'])->name('sms.reply');
    Route::get('/sms/view/{id}', [SMSController::class, 'getIndividualMsg'])->name('sms.view');

});


Route::prefix('admin')->group(function () {

    Auth::routes();
    Route::get('/logout', function () {
        Auth::logout();

        return redirect('admin/login');
    })->name('logout');
});
//});

