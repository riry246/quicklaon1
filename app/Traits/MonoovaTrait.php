<?php

// app/Traits/MonoovaTrait.php

namespace App\Traits;

use App\Models\MonoovaAccount;
use App\Models\Transaction;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait MonoovaTrait
{
    public function generateUID()
    {
        $uuid = Uuid::uuid4();
        return $uuid->toString();
    }

    public function createMonoovaAccount($user)
    {
        try {
            $client = new Client();

            $account_name = Str::limit($user->first_name . ' ' . $user->last_name, 28);

            $response = $client->request('POST', env('MONOOVO_API_URL') . 'receivables/v1/create', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . env('MONOOVO_API_KEY'),
                ],
                'json' => [
                    'bankaccountName' => $account_name . ' (CF)',
                    'ClientUniqueId' => '',
                    'isActive' => true,
                ],
            ]);

            $response = $response->getBody();
            $responseArray = json_decode($response, true);

            $account = MonoovaAccount::create([
                'status' => $responseArray['status'],
                'statusDescription' => $responseArray['statusDescription'],
                'bankAccountName' => $responseArray['bankAccountName'],
                'bankAccountNumber' => $responseArray['bankAccountNumber'],
                'bsb' => $responseArray['bsb'],
                'clientUniqueId' => $responseArray['clientUniqueId'],
                'isActive' => $responseArray['isActive'],
                'user_id' => $user->id,
            ]);

            return $account;
        } catch (RequestException $e) {
            Log::error("Error creating Monoova account: " . $e->getMessage());
            return false;
        }
    }

    public function generatePayID($accountName, $accountNumber)
    {
        try {
            $client = new Client();

            $response = $client->request('POST', env('MONOOVO_API_URL') . 'receivables/v1/payid/registerPayId', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . env('MONOOVO_API_KEY'),
                ],
                'json' => [
                    'bankAccountNumber' => $accountNumber,
                    'payIdName' => $accountName,
                    'payId' => '',
                ],
            ]);

            $response = $response->getBody();
            $responseArray = json_decode($response, true);

            return $responseArray;
        } catch (RequestException $e) {
            Log::error("Error generating PayID: " . $e->getMessage());
            return false;
        }
    }

    public function isActive($id)
    {
        try {
            $user = User::find($id);
            $monoovaAccount = $user->monoovaAccount;

            $client = new Client();

            $response = $client->request('GET', env('MONOOVO_API_URL') . 'receivables/v1/statusByBankAccount/' . $monoovaAccount->bankAccountNumber, [
                'headers' => [
                    'Authorization' => 'Basic ' . env('MONOOVO_API_KEY'),
                ],
            ]);

            $response = $response->getBody();
            $responseArray = json_decode($response, true);

            return $responseArray['isActive'];
        } catch (RequestException $e) {
            Log::error("Error checking Monoova account status: " . $e->getMessage());
            return false;
        }
    }

    public function changeAccountStatus($id, $status)
    {
        try {
            $user = User::find($id);
            $monoovaAccount = $user->monoovaAccount;

            $client = new Client();

            $response = $client->request('POST', env('MONOOVO_API_URL') . 'receivables/v1/status', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . env('MONOOVO_API_KEY'),
                ],
                'json' => [
                    'bankAccountNumber' => $monoovaAccount->bankAccountNumber,
                    'ClientUniqueId' => $monoovaAccount->clientUniqueId,
                    'isActive' => $status,
                ],
            ]);

            $response = $response->getBody();
            $responseArray = json_decode($response, true);

            $monoovaAccount->isActive = $responseArray['isActive'];
            $monoovaAccount->statusDescription = $responseArray['statusDescription'];
            $monoovaAccount->save();

            return $responseArray;
        } catch (RequestException $e) {
            Log::error("Error changing Monoova account status: " . $e->getMessage());
            return false;
        }
    }

    public function dispatchFund($loan)
    {
        try {
            $user = $loan->user;

            $bank_account = $loan->illionPrimaryAccount;
            $bsb = $bank_account->bsb;
            if (strpos($bsb, '-') === false) {
                // BSB doesn't contain a dash, so we need to insert it
                $bsb = substr_replace($bsb, '-', 3, 0);
            }
            $accountNumber = $bank_account->account_number;
            $accountNumber = str_replace(' ', '', $accountNumber);
            $accountName = $bank_account->account_holder;


            $lodgementReference = 'Loan Dispatched to ' . $user->first_name . ' ' . $user->last_name . ' #' . $user->basiq_user_id;

            $client = new Client();


            $response = $client->request('POST', env('MONOOVO_API_URL') . 'financial/v2/transaction/execute', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . env('MONOOVO_API_KEY'),
                ],
                'json' => [
                    'uniqueReference' => $this->generateUID(),
                    'disbursements' => [
                        [
                            'disbursementMethod' => 'NppCreditBankAccount',
                            'toNppCreditBankAccountDetails' => [
                                'bsbNumber' => $bsb, // Replace with your BSB
                                'accountNumber' => $accountNumber, // Replace with your account number
                                'accountName' => $accountName ?? $user->first_name . ' ' . $user->last_name,
                            ],
                            'lodgementReference' => $lodgementReference,
                            'amount' => $loan->approved_amount,
                        ],
                    ],
                ],
            ]);

            $response = $response->getBody();
            $responseArray = json_decode($response, true);

            $transactionStatus = $this->checkStatus($responseArray['callerUniqueReference']);

            // if ($loan->activeBankAccounts) {
            //     $transaction = $this->saveTransaction($loan, $user, null, $lodgementReference, $responseArray, $transactionStatus, $settlement_account, 'Debit');
            //  } else {
            $transaction = $this->saveIllionTransaction($loan, $user, null, $lodgementReference, $responseArray, $transactionStatus, $bank_account, 'Debit');
            //  }

            return true;
        } catch (RequestException $e) {

            $response = $e->getResponse();
            $reasonPhrase = $response->getReasonPhrase();
            Log::error("Error dispatching loan amount: $reasonPhrase");

            throw $e;
        }
    }

    public function directDebit($account, $loan, $instalment)
    {
        try {

            $user = $loan->user;
            $lodgementReference = 'Repayment of Loan from ' . $user->basiq_user_id . ' Loan ID#' . $loan->id;

            $bsb = substr($account, 0, 6);
            $bsb = substr_replace($bsb, '-', 3, 0);
            $accountNumber = substr($account, 6);
            $accountName = $user->first_name . ' ' . $user->last_name;

            $client = new Client();

            $response = $client->request('POST', env('MONOOVO_API_URL') . 'financial/v2/transaction/execute', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . env('MONOOVO_API_KEY'),
                ],
                'json' => [
                    'totalAmount' => $instalment->weekly_payment, // Replace with your amount
                    'paymentSource' => 'directDebit',
                    'lodgementReference' => 'cashfaster-' . $instalment->id, // Replace with your reference
                    'uniqueReference' => $this->generateUID(),
                    'directDebit' => [
                        'bsbNumber' => $bsb, // Replace with your BSB
                        'accountNumber' => $accountNumber, // Replace with your account number
                        'accountName' =>$accountName,
                    ],
                ],
            ]);

            $response = $response->getBody();
            $responseArray = json_decode($response, true);

            $transactionStatus = $this->checkStatus($responseArray['callerUniqueReference']);

            $transaction = $this->saveTransaction($loan, $user, $instalment, $lodgementReference, $responseArray, $transactionStatus, $account, 'Credit');

            return $transaction;

        } catch (RequestException $e) {
            throw $e;
        }
    }
    public function directDebitIllion($bank_account, $loan, $instalment)
    {
        try {


            $user = $loan->user;
            $lodgementReference = 'Repayment of Loan from ' . $user->basiq_user_id . ' Loan ID#' . $loan->id;

            $bsb = $bank_account->bsb;
            if (strpos($bsb, '-') === false) {
                // BSB doesn't contain a dash, so we need to insert it
                $bsb = substr_replace($bsb, '-', 3, 0);
            }
            $accountNumber = $bank_account->account_number;
            $accountNumber = str_replace(' ', '', $accountNumber);
            $accountName = $bank_account->account_holder;

            $client = new Client();

            $response = $client->request('POST', env('MONOOVO_API_URL') . 'financial/v2/transaction/execute', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . env('MONOOVO_API_KEY'),
                ],
                'json' => [
                    'totalAmount' => $instalment->weekly_payment, // Replace with your amount
                    'paymentSource' => 'directDebit',
                    'lodgementReference' => 'cashfaster-' . $instalment->id, // Replace with your reference
                    'uniqueReference' => $this->generateUID(),
                    'directDebit' => [
                        'bsbNumber' => $bsb, // Replace with your BSB
                        'accountNumber' => $accountNumber, // Replace with your account number
                        'accountName' => substr($accountName, 0, 32)
                    ],
                ],
            ]);

            $response = $response->getBody();
            $responseArray = json_decode($response, true);

            $transactionStatus = $this->checkStatus($responseArray['callerUniqueReference']);

            $transaction = $this->saveIllionTransaction($loan, $user, $instalment, $lodgementReference, $responseArray, $transactionStatus, $bank_account, 'Credit');

            return $transaction;

        } catch (RequestException $e) {
            throw $e;
        }
    }
    public function directDebitAll($account, $loan, $amount)
    {
        try {

            $user = $loan->user;
            $lodgementReference = 'Repayment of Loan from ' . $user->basiq_user_id . ' Loan ID#' . $loan->id;

            $bsb = substr($account, 0, 6);
            $bsb = substr_replace($bsb, '-', 3, 0);
            $accountNumber = substr($account, 6);
            $accountName = $user->first_name . ' ' . $user->last_name;

            $client = new Client();

            $response = $client->request('POST', env('MONOOVO_API_URL') . 'financial/v2/transaction/execute', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . env('MONOOVO_API_KEY'),
                ],
                'json' => [
                    'totalAmount' => $amount, // Replace with your amount
                    'paymentSource' => 'directDebit',
                    'lodgementReference' => 'cashfaster-' . $loan->id, // Replace with your reference
                    'uniqueReference' => $this->generateUID(),
                    'directDebit' => [
                        'bsbNumber' => $bsb, // Replace with your BSB
                        'accountNumber' => $accountNumber, // Replace with your account number
                        'accountName' => $accountName,
                    ],
                ],
            ]);

            $response = $response->getBody();
            $responseArray = json_decode($response, true);

            $transactionStatus = $this->checkStatus($responseArray['callerUniqueReference']);

            $transaction = $this->saveTransaction($loan, $user, null, $lodgementReference, $responseArray, $transactionStatus, $account, 'Credit');

            return $transaction;

        } catch (RequestException $e) {
            throw $e;
        }
    }
    public function directDebitAllIllion($bank_account, $loan, $amount)
    {
        try {

            $user = $loan->user;
            $lodgementReference = 'Repayment of Loan from ' . $bank_account->customerId . ' Loan ID#' . $loan->id;

            $bsb = $bank_account->bsb;
            if (strpos($bsb, '-') === false) {
                // BSB doesn't contain a dash, so we need to insert it
                $bsb = substr_replace($bsb, '-', 3, 0);
            }
            $accountNumber = $bank_account->account_number;
            $accountNumber = str_replace(' ', '', $accountNumber);
            $accountName = $bank_account->account_holder;

            $client = new Client();

            $response = $client->request('POST', env('MONOOVO_API_URL') . 'financial/v2/transaction/execute', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . env('MONOOVO_API_KEY'),
                ],
                'json' => [
                    'totalAmount' => $amount, // Replace with your amount
                    'paymentSource' => 'directDebit',
                    'lodgementReference' => 'cashfaster-' . $loan->id, // Replace with your reference
                    'uniqueReference' => $this->generateUID(),
                    'directDebit' => [
                        'bsbNumber' => $bsb, // Replace with your BSB
                        'accountNumber' => $accountNumber, // Replace with your account number
                        'accountName' => substr($accountName, 0, 32)
                    ],
                ],
            ]);

            $response = $response->getBody();
            $responseArray = json_decode($response, true);

            $transactionStatus = $this->checkStatus($responseArray['callerUniqueReference']);

            $transaction = $this->saveIllionTransaction($loan, $user, null, $lodgementReference, $responseArray, $transactionStatus, $bank_account, 'Credit');

            return $transaction;

        } catch (RequestException $e) {
            throw $e;
        }
    }

    public function saveTransaction($loan, $user, $instalment, $lodgementReference, $responseArray, $transactionStatus, $account, $type)
    {
        try {
            $transaction = new Transaction();
            $transaction->application_id = $loan->id;
            $transaction->user_id = $user->id;
            $transaction->description = $lodgementReference;
            if ($instalment) {
                $transaction->loan_statements_id = $instalment->id ?? null;
                $transaction->amount = number_format($instalment->weekly_payment, 2, '.', '');
            } else {
                $transaction->amount = number_format($loan->approved_amount, 2, '.', '');
            }

            $transaction->type = $type;
            $transaction->duration_ms = $responseArray['durationMs'];
            $transaction->status = $transactionStatus['transactionStatus'];
            $transaction->status_description = $transactionStatus['statusDescription'];
            $transaction->bpay_receipts = json_encode($responseArray['bpayReceipts']);
            $transaction->caller_unique_reference = $responseArray['callerUniqueReference'];
            $transaction->fee_amount_excluding_gst = $responseArray['feeAmountExcludingGst'];
            $transaction->fee_amount_gst_component = $responseArray['feeAmountGstComponent'];
            $transaction->fee_amount_including_gst = $responseArray['feeAmountIncludingGst'];
            $transaction->fee_breakdown = json_encode($responseArray['feeBreakdown']);
            //extra
            $transaction->completed_date = $transactionStatus['completedDate'];
            $transaction->credit_card_payment_status = $transactionStatus['creditCardPaymentStatus'];
            $transaction->dishonoured_date = $transactionStatus['dishonouredDate'];
            $transaction->expected_clearance_date_for_funds = $transactionStatus['expectedClearanceDateForFunds'];
            $transaction->funds_cleared_date = $transactionStatus['fundsClearedDate'];

            $transaction->transaction_id = $responseArray['transactionId'];

            $bsb = substr($account, 0, 6);
            $bsb = substr_replace($bsb, '-', 3, 0);
            $accountNumber = substr($account, 6);
            $accountName = $user->first_name . ' ' . $user->last_name;

            if ($type == 'Credit') {
                $transaction->account_number = $accountNumber;
                $transaction->account_name = $user->first_name . ' ' . $user->last_name;;
                $transaction->bsb =  $bsb;
                $transaction->institution = null;
            } else {
                $transaction->account_number = $accountNumber;
                $transaction->account_name = $user->first_name . ' ' . $user->last_name;;
                $transaction->bsb =  $bsb;
                $transaction->institution = null;
            }

            $transaction->save();

            return $transaction;

        } catch (RequestException $e) {
            throw $e;
        }
    }
    public function saveIllionTransaction($loan, $user, $instalment, $lodgementReference, $responseArray, $transactionStatus, $account, $type)
    {
        try {
            $transaction = new Transaction();
            $transaction->application_id = $loan->id;
            $transaction->user_id = $user->id;
            $transaction->description = $lodgementReference;
            if ($instalment) {
                $transaction->loan_statements_id = $instalment->id ?? null;
                $transaction->amount = number_format($instalment->weekly_payment, 2, '.', '');
            } else {
                $transaction->amount = number_format($loan->approved_amount, 2, '.', '');
            }

            $transaction->type = $type;
            $transaction->duration_ms = $responseArray['durationMs'];
            $transaction->status = $transactionStatus['transactionStatus'];
            $transaction->status_description = $transactionStatus['statusDescription'];
            $transaction->bpay_receipts = json_encode($responseArray['bpayReceipts']);
            $transaction->caller_unique_reference = $responseArray['callerUniqueReference'];
            $transaction->fee_amount_excluding_gst = $responseArray['feeAmountExcludingGst'];
            $transaction->fee_amount_gst_component = $responseArray['feeAmountGstComponent'];
            $transaction->fee_amount_including_gst = $responseArray['feeAmountIncludingGst'];
            $transaction->fee_breakdown = json_encode($responseArray['feeBreakdown']);
            //extra
            $transaction->completed_date = $transactionStatus['completedDate'];
            $transaction->credit_card_payment_status = $transactionStatus['creditCardPaymentStatus'];
            $transaction->dishonoured_date = $transactionStatus['dishonouredDate'];
            $transaction->expected_clearance_date_for_funds = $transactionStatus['expectedClearanceDateForFunds'];
            $transaction->funds_cleared_date = $transactionStatus['fundsClearedDate'];

            $transaction->transaction_id = $responseArray['transactionId'];
            if ($type == 'Credit') {
                $transaction->account_number = $account['account_number'];
                $transaction->account_name = $account['account_holder'] . '(' . $account['name'] . ')';
                $transaction->bsb = $account['bsb'];
                $transaction->institution = $account['institution'];
            } else {
                $transaction->account_number = $account->account_number;
                $transaction->account_name = $account->account_holder . '(' . $account->name . ')';
                $transaction->bsb = $account->bsb;
                $transaction->institution = $account->institution;
            }

            $transaction->save();

            return $transaction;

        } catch (RequestException $e) {
            throw $e;
        }
    }
    public function checkStatus($caller_unique_reference)
    {
        try {
            $client = new Client();

            $response = $client->request('GET', env('MONOOVO_API_URL') . 'financial/v2/status/' . $caller_unique_reference, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . env('MONOOVO_API_KEY'),
                ],
            ]);

            $response = $response->getBody();
            $responseArray = json_decode($response, true);

            return $responseArray;
        } catch (RequestException $e) {
            Log::error("Error checking transaction status: " . $e->getMessage());
            throw $e;
        }
    }

    public function accountDetail()
    {
        try {
            $client = new Client();

            $response = $client->request('GET', env('MONOOVO_API_URL') . 'mAccount/v1/financials/' . env('MONOOVO_ACCOUNT'), [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . env('MONOOVO_API_KEY'),
                ],
            ]);

            $response = $response->getBody();
            $responseArray = json_decode($response, true);

            return $responseArray;
        } catch (RequestException $e) {
            Log::error("Error checking transaction status: " . $e->getMessage());
            throw $e;
        }
    }
}
