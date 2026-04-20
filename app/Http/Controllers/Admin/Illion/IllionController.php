<?php

namespace App\Http\Controllers\Admin\Illion;

use App\Http\Controllers\Controller;
use App\Models\IllionBankAccount;
use App\Models\IllionCustomerInfo;
use App\Traits\IllionTrait;
use GuzzleHttp\Exception\RequestException;
use App\Models\IllionBank;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\IllionCost;

class IllionController extends Controller
{
    use IllionTrait;
    public function getBankList()
    {
        try {
            $institutionsData = $this->getInstitutions();

            if (isset($institutionsData['institutions'])) {
                foreach ($institutionsData['institutions'] as $institution) {
                    // Check if the bank slug already exists
                    $existingBank = IllionBank::where('slug', $institution['slug'])->first();

                    // Serialize the credentials array
                    $institution['credentials'] = json_encode($institution['credentials']);
                    // Convert timestamp to datetime strings
                    $institution['time_next_stats_cron'] = Carbon::createFromTimestamp($institution['time_next_stats_cron']);
                    $institution['time_next_session_cron'] = Carbon::createFromTimestamp($institution['time_next_session_cron']);
                    $institution['time_success_rate_updated'] = Carbon::createFromTimestamp($institution['time_success_rate_updated']);
                    $institution['time_next_outages_cron'] = isset($institution['time_next_outages_cron']) ? Carbon::createFromTimestamp($institution['time_next_outages_cron']) : null;




                    if ($existingBank) {
                        // If it exists, update the existing record
                        $existingBank->update($institution);
                    } else {
                        // If it doesn't exist, create a new record
                        IllionBank::create($institution);
                    }
                }
                return redirect()->back()->with('success', 'Institutions data updated/inserted successfully!');
            } else {
                echo "No institutions found in the response.";
            }

        } catch (RequestException $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function handleWebhook(Request $request)
    {
        try {
            $postData = $request->all();

            $illion = IllionCustomerInfo::firstOrNew([
                'reference' => $postData['reference'],
            ]);
            $illion->reference = $postData['reference'];
            $illion->encryptionKey = $postData['encryptionKey'];
            $illion->customerId = $postData['customerId'];
            $illion->save();

            return response()->json(['success' => true, 'message' => 'Data saved successfully']);

        } catch (\Exception $e) {
            // Log the error for debugging purposes
            \Log::error('Error in handleWebhook: ' . $e->getMessage());
            // Return an error response
            return response()->json(['success' => false, 'message' => 'An error occurred while processing the webhook'], 500);
        }
    }
    public function handleWebhookTest(Request $request)
    {
        try {
            $postData = $request->all();

            $illion = IllionCustomerInfo::firstOrNew([
                'reference' => $postData['reference'],
            ]);
            $illion->reference = $postData['reference'];
            $illion->encryptionKey = $postData['encryptionKey'];
            $illion->customerId = $postData['customerId'];
            $illion->save();

            return response()->json(['success' => true, 'message' => 'Data saved successfully']);

        } catch (\Exception $e) {
            // Log the error for debugging purposes
            $illion = new IllionCustomerInfo();
            $illion->customerId = json_encode($e->getMessage());
            $illion->save();
            \Log::error('Error in handleWebhook: ' . $e->getMessage());
            // Return an error response
            return response()->json(['success' => false, 'message' => 'An error occurred while processing the webhook'], 500);
        }
    }
    public function asyncReport(Request $request)
    {
        try {
            $postData = $request->all();

            $illion = new IllionCustomerInfo();
            $illion->reference = json_encode($postData);
            $illion->encryptionKey = $postData['customer']['encryptionKey'];
            $illion->customerId = $postData['customer']['customerId'];
            $illion->save();

            return response()->json(['success' => true, 'message' => 'Data saved successfully']);

        } catch (\Exception $e) {
            // Log the error for debugging purposes
            $illion = new IllionCustomerInfo();
            $illion->customerId = json_encode($e->getMessage());
            $illion->save();
            \Log::error('Error in handleWebhook: ' . $e->getMessage());
            // Return an error response
            return response()->json(['success' => false, 'message' => 'An error occurred while processing the webhook'], 500);
        }
    }

    public function getCustomerdata($id, $app_id)
    {
        try {

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

            foreach ($customerData->banks as $bankAccounts) {

                $illion->address = json_encode($bankAccounts->address);
                $illion->institution = $bankAccounts->bankName;

                foreach ($bankAccounts->bankAccounts as $bankAccount) {
                    $this->storeBankStatement($illion, $bankAccount, $app_id);

                }
            }

            $illion->encryptionKey = $customerData->customer->encryptionKey;
            $illion->decisionMetrics = json_encode($customerData->decisionMetrics);
            $illion->scoreModels = json_encode($customerData->scoreModels);
            $illion->zipFile = $report;
            $illion->filename = $htmlFile;
            $illion->save();

            return redirect()->back()->with('success', 'Bank data updated/inserted successfully!');
        } catch (RequestException $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function extractZipStatement($app_id)
    {
        $illion = IllionCustomerInfo::where('loanapplication_id', $app_id)->first();

        if ($illion && $illion->zipFile) {
            $htmlFile = $this->viewHtml($app_id);
            $illion->filename = $htmlFile;
            $illion->save();

            $filePath = storage_path('app/public/bankstatement/' . $illion->filename);

            // Check if the file exists before attempting to download
            if (file_exists($filePath)) {
                $fileUrl = asset('storage/bankstatement/' . $illion->filename);
                return redirect()->back()->with('fileUrl', $fileUrl)->with('success', 'Bank data updated/inserted successfully!');
            } else {
                return redirect()->back()->with('error', 'File not found.');
            }
        } else {
            return redirect()->back()->with('error', 'An error occurred: Please re-generate the statement from Generate or Update Bank Account button.');
        }
    }

    public function getIllionCost()
    {
        $customers = IllionCustomerInfo::all();

        foreach ($customers as $customer) {
            // Use updateOrCreate to update if a record with the same customerId exists, or create a new one
            IllionCost::updateOrCreate(
                ['customerId' => $customer->customerId], // Attributes to search for
                [
                    'user_id' => $customer->user_id,
                    'loanapplication_id' => $customer->loanapplication_id,
                    'amount' => 3.50,
                    'type' => 'Bank Statement',
                    'created_at' => $customer->created_at
                ]
            );
        }

        return redirect()->back()->with('success', 'Success');
    }

}
