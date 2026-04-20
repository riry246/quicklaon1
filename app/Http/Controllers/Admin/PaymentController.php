<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\LoanStatement;
use App\Models\MonoovaAccount;
use App\Models\User;
use App\Traits\BasiqTrait;
use App\Traits\GeneralTrait;
use App\Traits\LoanTrait;
use App\Traits\MessageTrait;
use App\Traits\MonoovaTrait;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use GuzzleHttp\Exception\ConnectException;
use Exception;
use Illuminate\Support\Facades\Log;
use Auth;

class PaymentController extends Controller
{
    use MonoovaTrait, GeneralTrait, LoanTrait, BasiqTrait, MessageTrait;

    public function approveLoan(Request $request, $id)
    {
        set_time_limit(320);
        try {
            $loan = LoanApplication::findOrFail($id);

            $request->validate([
                'otp' => 'required',
            ], [
                'otp.required' => 'Payment Authorization Code field is required',
            ]);

            // Verify Authorization Code
            if (!$this->VerifyAuthCodetoAdmin($request->otp)) {
                return redirect()->back()->with('error', 'Invalid or expired authorization code, Please check your mobile and re-enter the code');
            }

            //Check for generated statement
            $statement = $loan->statements;
            if (count($statement) > 0) {
                return redirect()->back()->with('error', 'Multiple attempt to dispatch loan is prohibited. Please contact system admin for futher assistance');
            }

            // Check and Create Monoova Account
            $this->checkMonoovaAccount($loan->user_id);

            // Generate Loan Statement
            $this->generateStatement($request, $loan);

            // Dispatch Loan Amount

            $bank_account = $loan->illionPrimaryAccount;

            $this->dispatchLoanAmount($loan);

            // Update loan status
            $loan->status = 'active';
            $loan->approved_date = now();
            $loan->approved_by = Auth::id();
            $loan->save();

            //Send Email Notification
            $datas = array(
                'user_id' => $loan->user_id,
                'template' => 'approved-application',
                'type' => 'mail',
                'loan_application_id' => $id,
            );
            $this->storeMsg($datas);

            //Push Notification
            $datas = array(
                'user_id' => $loan->user_id,
                'subject' => 'Approved application',
                'content' => 'Great news! You have been approved for $' . $loan->approved_amount . ' a loan with us.',
                'type' => 'inapp',
                'loan_application_id' => $id,
            );

            $this->storeMsg($datas);


            // Send Database Notification
            $notification['icon'] = "ti-file-check";
            $notification['color'] = "success";
            $notification['heading'] = "Application Approved";
            $notification['msg'] = "Congratulations! Your recent application has been approved.";

            $this->sendNotification($loan->user_id, $notification);

            $this->logActivity('Approval of Application ID #' . $id, null);



            return redirect()->back()->with('success', 'Loan has been approved successfully!');
        } catch (ConnectException $e) {
            Log::error("Connection error while processing payment: " . $e->getMessage());
            return $this->paymentError("Connection error while processing payment: " . $e->getMessage());
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $reasonPhrase = $response->getReasonPhrase();
                Log::error("Request error while processing payment: $statusCode - $reasonPhrase");
                return $this->paymentError($reasonPhrase);
            } else {
                Log::error("Request error without a response while processing payment: " . $e->getMessage());
                return $this->paymentError('Request error. Please try again later.');
            }
        }
    }

    function checkMonoovaAccount($id)
    {
        $user = User::find($id);
        $monoovaAccount = $user->monoovaAccount;


        if (!$monoovaAccount) {
            $account = $this->generateAccount($user);
            $accountNumber = $account['bankAccountNumber'];
            $accountName = $account['bankAccountName'];
            $accountID = $account['id'];

            sleep(30);
            $payid = $this->generatePayID($accountName, $accountNumber);

            $monoovaAccount = MonoovaAccount::FindorFail($accountID);
            $monoovaAccount->payid = $payid['PayIdDetails']['PayId'];
            $monoovaAccount->save();

        } else {
            $accountNumber = $monoovaAccount->bankAccountNumber;
            $accountName = $monoovaAccount->bankAccountName;
            $accountID = $monoovaAccount->id;
        }



        return;

    }
    function dispatchLoanAmount($loan)
    {
        $this->dispatchFund($loan);
    }

    function generateStatement($request, $loan)
    {
        //$settlement_day = $this->getUserAttrValue($loan->id, 'pay_day') ? $this->getUserAttrValue($loan->id, 'pay_day') : 'tuesday';

        $loanStatement = $this->reGenerateLoanStatement($loan->approved_amount, $loan->duration, $loan->first_repayment_date, $loan->frequency);

        foreach ($loanStatement as $ls) {
            $statement = new LoanStatement();
            $statement->loan_application_id = $loan->id;
            $statement->payment_date = $ls['payment_date'];
            $statement->settlement_date = $ls['settlement_date'];
            $statement->opening_balance = $ls['opening_balance'];
            $statement->weekly_payment = $ls['weekly_payment'];
            $statement->interest = $ls['interest'];
            $statement->principal_payment = $ls['principal_payment'];
            $statement->closing_balance = $ls['closing_balance'];
            $statement->payment_status = $ls['payment_status'];
            $statement->weekly_establishment_fee = $ls['weekly_establishment_fee'];
            $statement->weekly_interest = $ls['weekly_interest'];
            $statement->frequency = $loan->frequency;

            $statement->save();
        }

        return true;
    }

    function generateAccount($user)
    {
        $account = $this->createMonoovaAccount($user);
        $isActive = $this->isActive($user->id);

        if (!$isActive) {
            // Make Bank Account Active
            $this->changeAccountStatus($user->id, true);
        }

        return $account;

    }


    public function createPayID(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $monoovaAccount = $user->monoovaAccount;

            $account = $monoovaAccount ?? $this->generateAccount($user);

            sleep(30);

            $payid = $this->generatePayID($account['bankAccountName'], $account['bankAccountNumber']);

            $monoovaAccount = MonoovaAccount::findOrFail($account['id']);
            $monoovaAccount->payid = $payid['PayIdDetails']['PayId'];
            $monoovaAccount->save();

            return redirect()->back()->with('success', 'Monoova Account created successfully!');
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Resource not found.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function payInstalment($statement_id)
    {
        try {
            $instalment = LoanStatement::findOrFail($statement_id);
            $loan = LoanApplication::findOrFail($instalment->loan_application_id);

            $illionPrimaryAccount = $loan->illionPrimaryAccount;
            $bank_account = $loan->activeBankAccounts;
            $amount = $instalment->weekly_payment;

            if ($illionPrimaryAccount) {
                $transaction = $this->directDebitIllion($illionPrimaryAccount, $loan, $instalment);
            } else {
                $targetAccountNo = $bank_account->primary_account;
                $transaction = $this->directDebit($targetAccountNo, $loan, $instalment);
            }

            if ($transaction) {
                $instalment->payment_status = $transaction->status;
                $instalment->transaction_id = $transaction->id;
                $instalment->save();

            } else {
                return $this->paymentError("Oops something went wrong. Please contact Cashfaster Support team");
            }

            return $this->paymentSuccess('Payment of $' . $amount . ' has been successfully processed. Thank you for your payment!');
        } catch (ConnectException $e) {
            Log::error("Connection error while processing payment: " . $e->getMessage());
            return $this->paymentError("Connection error while processing payment: " . $e->getMessage());
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $reasonPhrase = $response->getReasonPhrase();
                Log::error("Request error while processing payment: $statusCode - $reasonPhrase");
                return $this->paymentError($reasonPhrase);
            } else {
                Log::error("Request error without a response while processing payment: " . $e->getMessage());
                return $this->paymentError('Request error. Please try again later.');
            }
        }
    }

    // Other methods...

    private function findAccountByAccountNo($accounts, $targetAccountNo)
    {
        foreach ($accounts as $account) {
            if ($account['accountNo'] === $targetAccountNo) {
                return $account;
            }
        }

        return null;
    }

    private function findAccountWithHigherFunds($accounts, $minimumAvailableFunds)
    {
        foreach ($accounts as $account) {
            if ($account['availableFunds'] > $minimumAvailableFunds) {
                return $account;
            }
        }

        return null;
    }

    private function paymentSuccess($message)
    {
        return redirect()->back()->with('success', $message);
    }

    private function paymentError($message)
    {
        return redirect()->back()->with('error', $message);
    }
}
