<?php

namespace App\Http\Controllers;

use App\Models\LeadMarketBuy;
use App\Models\LoanApplication;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Str;
use Auth;

class LeadWebhookController extends Controller
{
    use GeneralTrait;

    private $apiBuyerToken;
    private $apiBuyerKey;
    private $apiEndpoint;
    private $BuyerClientID;

    public function __construct()
    {
        $this->apiBuyerToken = env('LM_BUYER_API_TOKEN');
        $this->apiBuyerKey = env('LM_BUYER_API_KEY');
        $this->apiEndpoint = env('LM_URL');
        $this->BuyerClientID = env('LM_BUYER_CLIENT_ID');
    }
    public function LeadPurchase(Request $request)
    {
        // Process the lead purchase webhook data here
        $data = $request->all();
        $detail = $this->getLeadDetails($data['token']);
        $lead = $this->handleLeadPurchase($detail['Data']);

        //Create User 
        $user = $this->createUser($lead);

        //Create Loan Application
        $application = LoanApplication::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'incomplete', 'active', 'processing'])
            ->latest('created_at')
            ->first();
            

        if (!$application) {
            // Create a new application
            $data['amount'] = 2000;
            $data['duration'] = 12;
            $data['frequency'] = $request->input('frequency') ?: 'weekly';
            $data['status'] = 'pending';
            $data['user_id'] = $user->id;
            $data['step'] = 1;

            $application = $this->createApplication($data);


        }

        foreach ($detail['Data']['Lead']['Applicant']['Person_Applicant']['Contact_Methods']['Contact_Method'] as $c) {
            if (isset($c['Address'])) {
                $address = $c['Address'];
                $address_attr = array(
                    'route' => $address['Address_Line_1'] ?? '',
                    'administrative_area_level_2' => $address['Address_Line_2'] ?? '',
                    'administrative_area_level_1' => $address['State'] ?? '',
                    'locality' => $address['Suburb'] ?? '',
                    'postal_code' => $address['Postcode'] ?? '',
                    'address' => ($address['Address_Line_1'] ?? '') . ', ' . ($address['Suburb'] ?? '') . ', ' . ($address['State'] ?? '') . ' ' . ($address['Postcode'] ?? '')
                );

                // Insert User Attributes
                $this->insertUserattr($address_attr, $application->id, $user->id);

            }
        }

        Auth::loginUsingId($user->id);

        $lead->user_id = $user->id;
        $lead->loan_application_id = $application->id;
        $lead->save();

        return redirect()->route('application.steps', ['step' => 'apply?lead=' . $lead->lead_id . '&u=' . $user->id])->with('success', 'Please complete your application');

    }

    public function getLeadDetails($token)
    {
        try {

            $client = new Client();

            // Make a POST request to get lead details
            $response = $client->post($this->apiEndpoint . '/' . $this->apiBuyerKey . '/application/detail', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'API_Token' => $this->apiBuyerToken,
                    'Buyer_Token' => $token,
                    'Extra_Info_Only' => false,
                ],
            ]);

            // Check for successful response
            if ($response->getStatusCode() === 200) {

                // Return the result or handle as needed
                $result = json_decode($response->getBody()->getContents(), true);

                return $result;
            } else {
                // Handle non-successful HTTP status codes
                return response()->json(['error' => 'Unexpected HTTP status code'], $response->getStatusCode());
            }

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                // If there's a response, get the status code and body
                $statusCode = $e->getResponse()->getStatusCode();
                $errorBody = json_decode($e->getResponse()->getBody(), true);

                return response()->json(['error' => $errorBody, 'status_code' => $statusCode], $statusCode);
            } else {
                // If there's no response, it might be a network-related issue
                return response()->json(['error' => 'Network-related error'], 500);
            }

        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function handleLeadPurchase($data)
    {
        $leadData = $data['Lead']['Applicant']['Person_Applicant'];
        $contact = $data['Lead']['Applicant']['Person_Applicant']['V_Unique_Contact_Methods']['V_Unique_Contact_Method'];
        $leadId = $data['Lead']['Buyer_Data']['Lead_ID'];
        $csAppId = $data['Lead']['Buyer_Data']['CS_App_ID'];
        $token = $data['Lead']['Buyer_Data']['Buyer_Token'];
        $saleNetAmount = $data['Lead']['Buyer_Data']['Sale_Net_Amount'];
        $lead_price = $data['Lead']['Buyer_Data']['Lead_Price'];

        $lead = LeadMarketBuy::where('lead_id', $leadId)->first();

        if (!$lead) {
            $lead = new LeadMarketBuy();
        }

        foreach ($contact as $k => $v) {
            if (isset($contact[$k]['V_Unique_Contact_Other'])) {
                if ($contact[$k]['V_Unique_Contact_Other']['V_Unique_Contact_Type'] == 'EMAIL') {
                    $lead->email = $contact[$k]['V_Unique_Contact_Other']['V_Unique_Contact_Value'];
                } elseif ($contact[$k]['V_Unique_Contact_Other']['V_Unique_Contact_Type'] == 'MOB') {
                    $mobile = $contact[$k]['V_Unique_Contact_Other']['V_Unique_Contact_Value'];
                    $lead->mobile = '0' . ltrim(preg_replace('/^\+?61/', '', $mobile), '0');
                }
            }
        }

        $lead->first_name = $leadData['First_Name'] ?? null;
        $lead->middle_name = $leadData['Middle_Names'] ?? null;
        $lead->last_name = $leadData['Last_Name'] ?? null;
        $lead->date_of_birth = $leadData['Date_Of_Birth'] ?? null;
        $lead->lead_id = $leadId;
        $lead->saleNetAmount = $saleNetAmount;
        $lead->lead_price = $lead_price;
        $lead->token = $token;
        $lead->cs_app_id = $csAppId;
        $lead->response = json_encode($data);

        $lead->save();


        \Log::info('Lead Purchase Webhook data received:', $data);

        return $lead;
    }

    function timeoutNotification(Request $request)
    {

        $data = (object) $request->all(); // Convert $data array to stdClass object

        // Check if 'JSON' key exists in $data
        if (!isset($data->JSON)) {
            return response()->json(['error' => 'JSON data not found'], 400);
        }

        $result = json_decode($data->JSON);

        // Check if JSON decoding was successful
        if (!$result) {
            return response()->json(['error' => 'Invalid JSON data'], 400);
        }

        $leadData = $result->Lead->Applicant->Person_Applicant;
        $contact = $result->Lead->Applicant->Person_Applicant->V_Unique_Contact_Methods->V_Unique_Contact_Method;
        $leadId = $result->Lead->Buyer_Data->Lead_ID;
        $csAppId = $result->Lead->Buyer_Data->CS_App_ID;
        $token = $result->Lead->Buyer_Data->Buyer_Token;
        $saleNetAmount = $result->Lead->Buyer_Data->Sale_Net_Amount;
        $lead_price = $result->Lead->Buyer_Data->Lead_Price;

        $lead = LeadMarketBuy::where('lead_id', $leadId)->first();

        if (!$lead) {
            $lead = new LeadMarketBuy();
        }

        foreach ($contact as $k => $v) {
            if (isset($contact[$k]->V_Unique_Contact_Other)) { // Accessing object properties
                $contactType = $contact[$k]->V_Unique_Contact_Other->V_Unique_Contact_Type;
                $contactValue = $contact[$k]->V_Unique_Contact_Other->V_Unique_Contact_Value;

                // Check contact type and assign value accordingly
                if ($contactType == 'EMAIL') {
                    $lead->email = $contactValue;
                } elseif ($contactType == 'MOB') {
                    $mobile = '0' . ltrim(preg_replace('/^\+?61/', '', $contactValue), '0');
                    $lead->mobile = $mobile;
                }
            }
        }

        // Assuming 'Middle_Names' is an attribute of $leadData object
        $lead->first_name = $leadData->First_Name ?? null;
        $lead->middle_name = $leadData->Middle_Names ?? null; // Changed to object property access
        $lead->last_name = $leadData->Last_Name ?? null;
        $lead->date_of_birth = $leadData->Date_Of_Birth ?? null;
        $lead->lead_id = $leadId;
        $lead->saleNetAmount = $saleNetAmount;
        $lead->lead_price = $lead_price;
        $lead->token = $token;
        $lead->cs_app_id = $csAppId;
        $lead->response = json_encode($data); // $data is already an object

        $lead->save();

        return response()->json(['message' => 'Webhook request processed successfully'], 200);
    }
}
