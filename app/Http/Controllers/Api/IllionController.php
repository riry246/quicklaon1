<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IllionBankAccount;
use App\Models\IllionCustomerInfo;
use App\Models\User;
use App\Traits\GeneralTrait;
use App\Traits\IllionTrait;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;

class IllionController extends Controller
{

    use IllionTrait, GeneralTrait;
    public function getCustomerdata(Request $request)
    {
        try {

            $id = $request->input('user_id');
            $app_id = $request->input('app_id');


            $illion = IllionCustomerInfo::where('user_id', $id)->first();
            $oldZipFile = $illion->zipFile;
            $oldFile = $illion->filename;
            $address = null;
            $institution = null;

            $customerData = $this->getCustomerAccount($illion);
            $customerData = json_decode($customerData);

            $illion->encryptionKey = $customerData->customer->encryptionKey;
            $illion->decisionMetrics = json_encode($customerData->decisionMetrics);
            $illion->scoreModels = json_encode($customerData->scoreModels);
            $illion->save();

            $reportsLink = $customerData->reportsLink;

            //get zip files
            $report = $this->downloadHtml($reportsLink, $illion->customerId);
            $htmlFile = $this->viewHtml($app_id);

            //remove old zip file
            $this->removeOldZipFiles($oldZipFile);
            $this->removeOldZipFiles($oldFile);

            //save account information
            $affectedRows = IllionBankAccount::where('loanapplication_id', $app_id)->update(['status' => 0, 'primary_account' => 0]);

            $accountList = [];
            $i = 0;
            foreach ($customerData->banks as $bankAccounts) {
                $illion->address = json_encode($bankAccounts->address);
                $illion->institution = $bankAccounts->bankName;

                foreach ($bankAccounts->bankAccounts as $bankAccount) {
                    // Check if the accountNumber contains 'xxxx xxxx' pattern
                    // dd($bankAccount);
                    if (strpos($bankAccount->accountNumber, 'XXXX') === false) {
                        // Add to accountList if accountNumber does not match the pattern
                        $accountList[] = [
                            'accountNumber' => $bankAccount->accountNumber,
                            'accountName' => $bankAccount->accountName
                        ];
                    }
                    $this->storeBankStatement($illion, $bankAccount, $app_id);
                }
            }


            $illion->encryptionKey = $customerData->customer->encryptionKey;
            $illion->decisionMetrics = json_encode($customerData->decisionMetrics);
            $illion->scoreModels = json_encode($customerData->scoreModels);
            $illion->zipFile = $report;
            $illion->filename = $htmlFile;
            $illion->save();

            return response()->json(['success' => 'Application Information collected successfully', 'accountList' => $accountList], 200);

        } catch (RequestException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function updatePrimaryAccount(Request $request)
    {
        try {
            $inputs = $request->input();
            $user_id = $inputs['user_id'];
            $app_id = $inputs['app_id'];
            $this->addIllionBankInformation($inputs['formData'], $user_id, $app_id);

            $user = User::findorfail($user_id);
            $user->bank_verified = true;
            $user->save();

            return response()->json(['success' => 'New Bank Information collected successfully'], 200);

        } catch (RequestException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

}

