<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\LoanApplication;
use App\Models\Transaction;
use App\Traits\FormBuilderTrait;
use App\Traits\GeneralTrait;
use App\Traits\MonoovaTrait;
use App\Traits\TableBuilderTrait;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    private $module_name = 'Wallet Management';
    private $title = 'Wallet';
    private $module = 'wallet';
    private $url = 'wallet.send';


    use GeneralTrait, MonoovaTrait;
    public function send()
    {
        $data['pageTitle'] = $this->pageTitle($this->title, $this->module_name);
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Send money to other bank', $this->url, null);


        $data['moonova'] = $this->accountDetail();
        $data['bankList'] = Bank::get();
        $data['title'] = 'Send money to other bank account';
        $data['method'] = 'send';
        return view('admin.wallet.send', $data);

    }

    public function process(Request $request)
    {
        try {
            $data = $request->all();

            // Validating the request data
            $request->validate([
                'bsb' => 'required|numeric',
                'accountNo' => 'required|numeric',
                'remarks' => 'required|string|max:255',
                'amount' => 'required|numeric',
                'otp' => 'required|array', // Assuming 'otp' is an array
                'otp.*' => 'required|string', // Assuming 'otp' elements are strings
                'accountName' => 'required',
                'institution' => 'required',
            ], [
                // Custom error messages
                'bsb.required' => 'BSB number field is required',
                'accountName.required' => 'Account name field is required',
                'accountNo.required' => 'Account Number field is required',
                'amount.required' => 'Amount field is required',
                'remarks.required' => 'Remarks field is required',
                'otp.required' => 'Transaction code field is required',
                'institution.required' => 'Please Select Bank',
            ]);

            // Verify Authorization Code
            $mobiles = array('0499936355', '0415166323');
            $errorCount = 0;

            foreach ($mobiles as $mobile) {
                foreach ($request->otp as $otp) {
                    if (!$this->VerifyAuthCodetoAdminbyNumber($mobile, $otp)) {
                        $errorCount++;
                        // Optionally, you can return a response immediately if an error is encountered
                        // return redirect()->back()->with('error', 'Invalid or expired authorization code, Please check your mobile and re-enter the code');
                    }
                }
            }

            if ($errorCount > 2) {
                return redirect()->back()->with('error', 'Invalid or expired authorization code, Please check your mobile and re-enter the code');
            }

            // Process the transaction data
            $inputs = [
                'institution' => $data['institution'],
                'accountName' => $data['accountName'],
                'bsb' => substr_replace($data['bsb'], '-', 3, 0),
                'accountNumber' => $data['accountNo'],
                'amount' => $data['amount'],
                'lodgementReference' => $data['remarks'],
                'application_id' => isset($data['application_id']) ? $data['application_id'] : null,
                'fullaccount' => $data['bsb'] . $data['accountNo']
            ];


            if ($data['method'] == 'send') {
                $this->sendMoney($inputs);
            } else {
                $this->depositMoney($inputs);
            }

            // Transaction successful
            return redirect()->back()->with('success', 'Congratulations! Your transaction has been successfully completed.');
        } catch (RequestException $e) {
            // Handle request exception
            $response = $e->getResponse();
            $reasonPhrase = $response->getReasonPhrase();
            return redirect()->back()->with('error', $reasonPhrase);
        }
    }

    public function deposit()
    {
        $data['pageTitle'] = $this->pageTitle($this->title, $this->module_name);
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Send money to other bank', $this->url, null);


        $data['moonova'] = $this->accountDetail();
        $data['bankList'] = Bank::get();
        $data['title'] = 'Deposit Money to Monoova';
        $data['method'] = 'deposit';
        return view('admin.wallet.send', $data);

    }

    public function sendMoney($inputs)
    {

        try {
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
                                'bsbNumber' => $inputs['bsb'],
                                'accountNumber' => $inputs['accountNumber'],
                                'accountName' => $inputs['accountName']
                            ],
                            'lodgementReference' => $inputs['lodgementReference'],
                            'amount' => $inputs['amount'],
                        ],
                    ],
                ],
            ]);

            $response = $response->getBody();
            $responseArray = json_decode($response, true);

            $transactionStatus = $this->checkStatus($responseArray['callerUniqueReference']);

            $transaction = $this->saveTrans($inputs, $responseArray, $transactionStatus, 'Debit');


            return redirect()->back()->with('success', 'Congratulations! Your transaction has been successfully completed.');
        } catch (RequestException $e) {

            $response = $e->getResponse();
            $reasonPhrase = $response->getReasonPhrase();

            throw $e;
        }
    }

    public function depositMoney($inputs)
    {
        try {

            $client = new Client();

            $response = $client->request('POST', env('MONOOVO_API_URL') . 'financial/v2/transaction/execute', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . env('MONOOVO_API_KEY'),
                ],
                'json' => [
                    'totalAmount' => $inputs['amount'], // Replace with your amount
                    'paymentSource' => 'directDebit',
                    'lodgementReference' => 'CF-' . $this->generateReferralCode(), // Replace with your reference
                    'uniqueReference' => $this->generateUID(),
                    'directDebit' => [
                        'bsbNumber' => $inputs['bsb'], // Replace with your BSB
                        'accountNumber' => $inputs['accountNumber'],
                        'accountName' => $inputs['accountName']
                    ],
                ],
            ]);

            $response = $response->getBody();
            $responseArray = json_decode($response, true);

            $transactionStatus = $this->checkStatus($responseArray['callerUniqueReference']);

            $transaction = $this->saveTrans($inputs, $responseArray, $transactionStatus, 'Credit');


            return $transaction;

        } catch (RequestException $e) {
            throw $e;
        }
    }

    public function saveTrans($inputs, $responseArray, $transactionStatus, $type)
    {
        try {

            if ($inputs['application_id']) {
                $loan = LoanApplication::find($inputs['application_id']);
                $user_id = $loan->user_id;

            } else {
                $user_id = Auth::id();
            }


            $transaction = new Transaction();
            $transaction->user_id = $user_id;
            $transaction->application_id = isset($inputs['application_id']) ? $inputs['application_id'] : null;
            $transaction->description = $inputs['lodgementReference'];
            $transaction->amount = number_format($inputs['amount'], 2, '.', '');
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
            $transaction->account_number = $inputs['fullaccount'];
            $transaction->account_name = $inputs['accountName'];
            $transaction->bsb = $inputs['fullaccount'];
            $transaction->institution = $inputs['institution'];


            $transaction->save();

            return $transaction;

        } catch (RequestException $e) {
            throw $e;
        }
    }
}
