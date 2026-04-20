<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeadMarket;
use App\Models\LeadMarketBuy;
use App\Models\LoanApplication;
use App\Traits\GeneralTrait;
use App\Traits\LeadMarketTrait;
use App\Traits\MessageTrait;
use App\Traits\TableBuilderTrait;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Auth;


class LeadMarketBoughtController extends Controller
{
    use LeadMarketTrait, GeneralTrait, TableBuilderTrait, MessageTrait;

    private $apiBuyerToken;
    private $apiBuyerKey;
    private $apiEndpoint;
    private $BuyerClientID;

    private $module_name = 'Lead Market';
    private $title = 'Lead Market';
    private $module = 'leadmarket.bought';
    private $url = 'leadmarket.bought.index';

    public function __construct()
    {
        $this->apiBuyerToken = env('LM_BUYER_API_TOKEN');
        $this->apiBuyerKey = env('LM_BUYER_API_KEY');
        $this->apiEndpoint = env('LM_URL');
        $this->BuyerClientID = env('LM_BUYER_CLIENT_ID');
    }

    public function index()
    {
        $data['tabel_fields'] = array(
            'id',
            'lead_id',
            'cs_app_id',
            'user',
            'email',
            'mobile',
            'loan_id',
            'lead_price',
            'saleNetAmount',
            'created_at'
        );
        //Page Title
        $data['pageTitle'] = $this->pageTitle($this->title, $this->module_name);

        //Button
        $data['btn'] = $this->disableButton($this->module);

        //Manage Action Button
        $data['action_btn'] = $this->actionViewButton($this->module);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Lead', $this->url, null);

        $data['list'] = LeadMarketBuy::select('*', 'user_id as user', 'loan_application_id as loan_id')->get();

        return view('admin.general.list', $data);
    }


    public function view($id)
    {

        $data['lead'] = LeadMarketBuy::find($id);
        $data['breadcrumb'] = $this->breadcrumb('Lead Market', 'Lead ID# ' . $data['lead']->lead_id, $this->url, null);


        $data['detail'] = $this->getLeadDetails($data['lead']->token);


        return view('admin.leadMarket.detail', $data);


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
    function sendEmailNotification($user_id, $loan_id, $template)
    {

        $data = array(
            'user_id' => $user_id,
            'template' => $template,
            'type' => 'mail',
            'loan_application_id' => $loan_id,
        );


        $this->storeMsg($data);


        //send Inapp Notification
        $datas = array(
            'user_id' => $user_id,
            'subject' => 'Application Declined',
            'content' => "We regret to inform you that your loan application has been declined.",
            'type' => 'inapp',
            'loan_application_id' => $loan_id,
        );

        $this->storeMsg($datas);

        return;
    }


    function leadUpdate($id)
    {
        $lead = LeadMarketBuy::find($id);
       
        $user = $lead->user_id;
        $detail = $this->getLeadDetails($lead->token);

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
                $this->insertUserattr($address_attr, $lead->loan_application_id, $lead->user_id);

            }
        }

        return redirect()->back()->with('success', 'Credit Score generated successfully!');
    }
}
