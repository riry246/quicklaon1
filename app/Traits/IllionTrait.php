<?php

namespace App\Traits;


use App\Models\IllionBankAccount;
use App\Models\IllionCost;
use App\Models\IllionCustomerInfo;
use App\Models\LoanApplication;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

trait IllionTrait
{
    use GeneralTrait;
    private $access_token = null;

    public function getInstitutions()
    {
        $client = new Client();

        try {
            $response = $client->request('GET', env('ILLION_BASE_URL') . 'institutions', [
                'headers' => [
                    'X-API-KEY' => env('ILLION_API_KEY'),
                    'Content-Type' => 'application/json'
                ]
            ]);

            $responseData = $response->getBody()->getContents();
            $institutionsData = json_decode($responseData, true);

            return $institutionsData; // Return the response data

        } catch (RequestException $e) {
            throw $e; // Throw the exception to be caught by the calling method
        }
    }

    public function initialise($user_id, $app_id)
    {

        $client = new Client();

        try {

            $checkUser = IllionCustomerInfo::where('user_id', $user_id)
                ->where('loanapplication_id', $app_id)
                ->first();

            if ($checkUser) {
                $referenceNumber = $checkUser->reference;
                $userRefId = $checkUser->customerId;

                $loanApplication = LoanApplication::findOrFail($checkUser->loanapplication_id);
                if ($loanApplication->status == 'incomplete') {
                    $redirect_url = env('ILLION_REDIRECT_URL');
                } else {
                    $redirect_url = env('ILLION_REDIRECT_URL_UPDATE');
                }



            } else {
                $referenceNumber = $this->generateUniqueRefNumber();
                $userRefId = $this->generateUniqueUserRefNumber($user_id);
                $redirect_url = env('ILLION_REDIRECT_URL');
            }



            $response = $client->post(env('ILLION_BASE_URL') . 'customer/initialise', [
                'headers' => [
                    'X-API-KEY' => env('ILLION_API_KEY'),
                    'Content-Type' => 'application/json',
                    'X-OUTPUT-VERSION' => '20190901'
                ],
                'json' => [
                    'reference' => $referenceNumber,
                    'link_expiry' => 30000,
                    'customer_name' => $this->getUserName($user_id),
                    'return_type' => 'application/json',
                    'request_centrelink' => false,
                    'exclude_bankstatements' => false,
                    'redirect_url' => $redirect_url,
                    'user_ref' => $userRefId,
                    'request_export_selection' => false,
                    'with_transactions' => false
                ]
            ]);

            $responseData = $response->getBody()->getContents();

            $responseData = json_decode($responseData, true);
            $responseData['user_ref'] = $userRefId;

            return $responseData;

        } catch (RequestException $e) {
            throw $e; // Throw the exception to be caught by the calling method
        }

    }

    function generateUniqueRefNumber()
    {
        $timestamp = time();

        $random = mt_rand(1000000000, 9999999999);
        $refNumber = 'CF-' . $timestamp . '-' . $random;

        return $refNumber;
    }

    function generateUniqueUserRefNumber($user_id)
    {
        $timestamp = time();

        $random = mt_rand(1000000000, 9999999999);
        $refNumber = 'CF-' . $timestamp . '-' . $random . '-' . $user_id;

        return $refNumber;
    }

    function getCustomerAccount($data)
    {

        $client = new Client();

        try {
            $response = $client->post(env('ILLION_BASE_URL') . 'customer/data', [
                'headers' => [
                    'X-API-KEY' => env('ILLION_API_KEY'),
                    'Content-Type' => 'application/json',
                    'X-OUTPUT-VERSION' => '20190901'
                ],
                'json' => [
                    'customerId' => $data->customerId,
                    'encryptionKey' => $data->encryptionKey,
                    "generateRawFile" => true,
                    "requestNumDays" => 180,
                    // "itrs_required" => true,
                    //  "async" => true,
                    //  "callbackUrl" => "https://app.cashfaster.com.au/illion_async_report"
                ]
            ]);

            $responseData = $response->getBody()->getContents();

            $this->illionCostCalculation($data->customerId);


            return $responseData;
        } catch (RequestException $e) {
            throw $e;
        }
    }

    function illionCostCalculation($customerId)
    {
        $existingCost = IllionCost::where('customerId', $customerId)->first();
        $amount = $existingCost ? 0.60 : 3.50;

        $cost = new IllionCost();
        $cost->customerId = $customerId;
        $cost->amount = $amount;
        $cost->type = 'Bank Statement';
        $cost->save();

        return;
    }

    function downloadHtml($reportsLink, $customerId)
    {
        $client = new Client();
        try {
            $response = $client->get(env('VITE_ILLION_BASE_URL') . '/' . $reportsLink, [
                'headers' => [
                    'X-API-KEY' => env('ILLION_API_KEY'),
                    'Content-Type' => 'application/json',
                    'X-OUTPUT-VERSION' => '20190901'
                ]
            ]);

            // Get the content disposition header to extract the filename
            $filename = $customerId . '_' . time() . '.zip';

            // Save the response body contents to a file in the public/storage/bankstatement directory
            Storage::put('public/bankstatement/' . $filename, $response->getBody());

            return $filename;

        } catch (RequestException $e) {
            throw $e;
        }
    }

    function storeBankStatement($illion, $bankAccount, $app_id)
    {
        try {
            $account = IllionBankAccount::firstOrNew([
                'customerId' => $illion->customerId,
                'account_number' => $bankAccount->accountNumber,
            ]);

            // Update or create a new record if it doesn't exist
            $account->customerId = $illion->customerId ?? null;
            $account->loanapplication_id = $app_id ?? null;
            $account->account_holder = $bankAccount->accountHolder ?? null;
            $account->name = $bankAccount->accountName ?? null;
            $account->bsb = $bankAccount->bsb ?? null;
            $account->balance = $bankAccount->currentBalance ?? 0.0;
            $account->available = $bankAccount->availableBalance ?? 0.0;
            $account->account_holder_type = $bankAccount->accountHolderType ?? null;
            $account->account_type = $bankAccount->accountType ?? null;
            $account->institution = $illion->institution ?? null;
            $account->statement = null;
            $account->transactions = json_encode($bankAccount->transactions);
            $account->statementSummary = json_encode($bankAccount->statementSummary);
            $account->statementAnalysis = json_encode($bankAccount->statementAnalysis);
            $account->additionalDetails = json_encode($bankAccount->additionalDetails);
            $account->status = 1;
            $account->save();

            return true;

        } catch (RequestException $e) {
            echo $e->getMessage();
        }
    }

    function removeOldZipFiles($filename)
    {
        try {
            $files = Storage::delete('public/bankstatement/' . $filename);

            return true;

        } catch (RequestException $e) {
            throw $e;
        }
    }

    public function viewHtml($app_id)
    {
        $illion = IllionCustomerInfo::where('loanapplication_id', $app_id)->first();

        if (!$illion) {
            return false;
        }

        $zipFilename = $illion->zipFile;
        $zipFilePath = storage_path("app/public/bankstatement/{$zipFilename}");

        if (!Storage::exists("public/bankstatement/{$zipFilename}")) {
            return false;
        }

        // Initialize a new ZipArchive object
        $zip = new ZipArchive();

        // Open the zip file
        if ($zip->open($zipFilePath) === true) {
            // Get the HTML file names inside the zip
            $htmlFiles = [];
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                if (pathinfo($filename, PATHINFO_EXTENSION) === 'html') {
                    // Extract the HTML file to the same directory
                    $zip->extractTo(dirname($zipFilePath), [$filename]);
                    $htmlFilePath = $filename;
                    // Close the zip file
                    $zip->close();
                    // Return the path to the extracted HTML file
                    return $htmlFilePath;
                }
            }
            // Close the zip file
            $zip->close();
        }

        return false;
    }

    public function generateConsumerAffordability($id)
    {
        $loanApplication = LoanApplication::findOrFail($id);
        $accounts = $loanApplication->illionBankAccount;
        $primary = $loanApplication->illionPrimaryAccount;

        if (!$primary) {
            if (isset($accounts[0])) {
                $this->setPrimaryAccount($accounts[0]->id);
            }

        }

        $analysis = [];

        foreach ($accounts as $account) {
            if ($account->statementAnalysis) {
                $accountAnalysis = json_decode($account->statementAnalysis, true); // decode JSON as associative array
                $accountNumber = $account->account_number;

                // Merge account analysis into $analysis array based on account number
                if (!isset($analysis[$accountNumber])) {
                    $analysis[$accountNumber] = $accountAnalysis;
                }
            }
        }
        //  dd($analysis);
        // Merge arrays with similar names
        $mergedAnalysis = [];
        $i = 0;
        foreach ($analysis as $accountNumber => $accountData) {
            $total = 0;
            foreach ($accountData as $category => $data) {
                $categoryName = $data['analysisCategory']['name'];
                $mergedAnalysis[$categoryName][$accountNumber] = $data;
            }

        }

        return $mergedAnalysis;
    }

    public function setPrimaryAccount($id)
    {
        $account = IllionBankAccount::find($id);
        $account->primary_account = 1;
        $account->save();
        return;
    }


}