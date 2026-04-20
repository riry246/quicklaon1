<?php

namespace App\Http\Controllers\Admin\Monoova;

use App\Http\Controllers\Controller;
use App\Models\LoanStatement;
use App\Models\MonoovaAccount;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Carbon\Carbon;

class WebHookController extends Controller
{
    use GeneralTrait;
    public function NPPReceivePayment(Request $request)
    {
       // $transaction = $this->testJson();

        $transaction = json_encode($request->all());
        $transaction = json_decode($transaction);

        $payid = $transaction->PayId;
        $totalAmount = $transaction->Amount;
        $completedStatements = [];

        $moonovaAccount = MonoovaAccount::where('payid', $payid)->first();

        if ($moonovaAccount) {
            $user = User::find($moonovaAccount->user_id);
            $loan = $user->latestLoanApplication;

            if ($payid == 'di76x0@cashfaster.com.au') {
                $description = 'Principle Amount of $' . number_format($transaction->Amount, 2, '.', '') . ' Deposited';
            } else {
                $loanstatements = $loan->unpaidstatement;
                foreach ($loanstatements as $loanstatement) {
                    if ($loanstatement->weekly_payment <= $totalAmount) {
                        $loanstatement->update(['payment_status' => 'Complete']);
                        $totalAmount -= $loanstatement->weekly_payment;
                        $completedStatements[] = $loanstatement->id;
                    }
                }
                $description = 'Rapayment of Loan from ' . $user->basiq_user_id . ' Loan ID#' . $loan->id;

                if ($totalAmount > 0) {
                    $description = 'Rapayment of Loan from ' . $user->basiq_user_id . ' Loan ID#' . $loan->id . 'Excess amount after payment $' . $totalAmount;
                }
            }

        }

        $dateString = $transaction->DateTime;
        $date = Carbon::createFromFormat('d/m/Y h:i:s A', $dateString);

        $newTrasnaction = new Transaction();
        $newTrasnaction->application_id = $loan->id ?? 0;
        $newTrasnaction->user_id = $user->id ?? 0;
        $newTrasnaction->description = $description;
        $newTrasnaction->loan_statements_id = implode(',', $completedStatements);
        $newTrasnaction->amount = number_format($transaction->Amount, 2, '.', '');

        $newTrasnaction->type = 'Credit';
        $newTrasnaction->status = 'Complete';
        $newTrasnaction->status_description = $transaction->PaymentDescription ?? 'Cleared';
        $newTrasnaction->completed_date = $date->format('Y-m-d');
        $newTrasnaction->funds_cleared_date = $date->format('Y-m-d');
        $newTrasnaction->transaction_id = $transaction->TransactionId;
        $newTrasnaction->bpay_receipts = 0;
        $newTrasnaction->caller_unique_reference = $transaction->TransactionId;
        $newTrasnaction->fee_amount_excluding_gst = 0;
        $newTrasnaction->fee_amount_gst_component = 0;
        $newTrasnaction->fee_amount_including_gst = 0;
        $newTrasnaction->fee_breakdown = 0;
        $newTrasnaction->duration_ms = 0;


        $newTrasnaction->account_number = str_replace('-', '', $transaction->Bsb) . $transaction->AccountNumber;
        $newTrasnaction->account_name = $transaction->AccountName;
        $newTrasnaction->bsb = str_replace('-', '', $transaction->Bsb);
        $newTrasnaction->institution = 'Pay ID';

        $newTrasnaction->webhookresponse = json_encode($request->all());
        $newTrasnaction->save();


        if ($completedStatements) {

            foreach ($completedStatements as $c) {

                $statement = LoanStatement::find($c);
                $statement->transaction_id = $newTrasnaction->id;
                $statement->save();
            }
        }

        echo 'Done';

        //$json = json_decode($this->testJson());


    }

    public function subscribes()
    {
        try {
            $client = new Client();

            $response = $client->request('POST', env('MONOOVO_API_URL') . 'subscriptions/v1/create', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . env('MONOOVO_API_KEY'),
                ],
                'json' => [
                    'eventName' => 'NPPReceivePayment',
                    'targetUrl' => 'https://admin.cashfaster.com.au/npp-receive-payment',
                    'subscriptionStatus' => 'On',
                    'securityToken' => 'Basic ' . env('MONOOVO_API_KEY'),
                ]
            ]);

            echo $response->getBody();
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle request exceptions
            echo "Request Exception: " . $e->getMessage();
        } catch (\Exception $e) {
            // Handle other exceptions
            echo "Exception: " . $e->getMessage();
        }
    }
    public function subscribe()
    {
        try {
            $client = new Client();

            $response = $client->request('GET', env('MONOOVO_API_URL') . 'subscriptions/v1/list', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . env('MONOOVO_API_KEY'),
                ],
            ]);

            // Decode the JSON response
            $data = json_decode($response->getBody(), true);

            // Check if the response status is "Ok"
            if ($data['status'] === 'Ok') {
                // Loop through each eventName and echo its details
                foreach ($data['eventName'] as $event) {
                    $this->update($event['id']);
                    echo "Event Name: " . $event['eventName'] . ", ID: " . $event['id'] . PHP_EOL;
                }
            } else {
                echo "Error: " . $data['statusDescription'];
            }

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle request exceptions
            echo "Request Exception: " . $e->getMessage();
        } catch (\Exception $e) {
            // Handle other exceptions
            echo "Exception: " . $e->getMessage();
        }
    }

    public function update($id)
    {
        try {
            $client = new Client();

            $response = $client->request('POST', env('MONOOVO_API_URL') . 'subscriptions/v1/update', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . env('MONOOVO_API_KEY'),
                ],
                'json' => [
                    'id' => $id,
                    'targetUrl' => 'https://admin.cashfaster.com.au/npp-receive-payment',
                    'subscriptionStatus' => 'On',
                    'securityToken' => 'Basic ' . env('MONOOVO_API_KEY'),
                ]
            ]);

            echo $response->getBody();
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle request exceptions
            echo "Request Exception: " . $e->getMessage();
        } catch (\Exception $e) {
            // Handle other exceptions
            echo "Exception: " . $e->getMessage();
        }
    }

    public function testJson()
    {
        $json = '{"TransactionId":"N00944515426","DateTime":"15\/04\/2024 3:15:18 PM","RemitterName":"DUNCAN MCINTYRE","Amount":"1.0000","AccountName":"CashFaster Lending  Account (CF)","AccountNumber":"425628199","Bsb":"802-985","PaymentDescription":"Test","PayId":"di76x0@cashfaster.com.au","PayIdName":"Payfaster Pty Ltd","EndToEndId":"NOTPROVIDED","CategoryPurposeCode":null,"CreditorReferenceInformation":null,"USINumber":null,"USICreditorScheme":null,"UltimateCreditorName":null,"ReconciliationRuleReference":null}';

        return json_decode($json);
    }

}
