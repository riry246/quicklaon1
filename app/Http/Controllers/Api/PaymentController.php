<?php



namespace App\Http\Controllers\Api;



use App\Http\Controllers\Controller;

use App\Models\LoanApplication;

use App\Models\LoanStatement;

use GuzzleHttp\Exception\RequestException;

use App\Traits\BasiqTrait;

use App\Traits\GeneralTrait;

use App\Traits\LoanTrait;

use App\Traits\MonoovaTrait;

use Illuminate\Http\Request;

use GuzzleHttp\Exception\ConnectException;

use Exception;

use Illuminate\Support\Facades\Log;



class PaymentController extends Controller
{

    use MonoovaTrait, GeneralTrait, LoanTrait, BasiqTrait;

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

    public function payall($loan_id)
    {
        try {

            $loan = LoanApplication::findOrFail($loan_id);
            $user = $loan->user;
            $amount = $this->totalPayableAmount($loan_id);
            $unpaidStatment = $loan->unpaidstatement;

            $illionPrimaryAccount = $loan->illionPrimaryAccount;
            $bank_account = $loan->activeBankAccounts;
            $basiq_user_id = $user->basiq_user_id;

            if ($illionPrimaryAccount) {
                $transaction = $this->directDebitAllIllion($illionPrimaryAccount, $loan, $amount);
            } else {
                $targetAccountNo = $bank_account->primary_account;
                $transaction = $this->directDebitAll($targetAccountNo, $loan, $amount);
            }

            if ($transaction) {
                foreach ($unpaidStatment as $s) {
                    $instalment = LoanStatement::find($s->id);
                    $instalment->payment_status = $transaction->status;
                    $instalment->transaction_id = $transaction->id;
                    $instalment->save();
                }
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

        return response()->json([

            "success" => true,

            "message" => $message

        ], 200);

    }



    private function paymentError($message)
    {

        return response()->json([

            "success" => false,

            "message" => $message

        ], 429);



    }



    public function reschedulePayment($id)
    {

        try {

            $loanStatement = LoanStatement::find($id);



            $loan = LoanApplication::find($loanStatement->loan_application_id);

            $rescheduleStatement = $loan->rescheduledstatement;



            if (count($rescheduleStatement) >= 2) {

                return response()->json([

                    "success" => false,

                    "message" => 'Payment rescheduling failed. You cannot reschedule payment more than 2 times for the same loan application.'

                ], 429);



            }



            $this->createReschedulePayment($loanStatement);



            return response()->json([

                "success" => true,

                "message" => 'Payment schedule for Statement ID ' . $id . ' has been successfully re-scheduled. The new payment details are updated.'

            ], 200);



        } catch (Exception $e) {

            return response()->json([

                "success" => false,

                "message" => 'Oops! something went wrong'

            ], 429);

        }

    }

}

