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


class LeadMarketController extends Controller
{
    use LeadMarketTrait, GeneralTrait, TableBuilderTrait, MessageTrait;
    private $apiSellerToken;
    private $apiSellerKey;
    private $apiEndpoint;
    private $sellerClientID;

    private $module_name = 'Lead Market';
    private $title = 'Lead Market';
    private $module = 'leadmarket';
    private $url = 'leadmarket.index';

    public function __construct()
    {
        $this->apiSellerToken = env('LM_SELLER_API_TOKEN');
        $this->apiSellerKey = env('LM_SELLER_API_KEY');
        $this->apiEndpoint = env('LM_URL');
        $this->sellerClientID = env('LM_SELLER_CLIENT_ID');
    }

    public function index()
    {
        $data['tabel_fields'] = array(
            'id',
            'lead_id',
            'lead_token',
            'user',
            'loan_application_id',
            'status',
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

        $data['list'] = LeadMarket::select('*', 'user_id as user')->get();

        return view('admin.general.list', $data);
    }

    public function declineLoan(Request $request, $id)
    {
        $data = $request->all();
        $documentData = $request->document_type;

        //Validation
        $request->validate(
            [
                'rejection_reason' => 'required',
            ],
            [
                'rejection_reason.required' => 'Rejection Reason field is required',
            ]
        );

        $loan = LoanApplication::findOrFail($id);
        $loan->status = 'declined';
        $loan->rejection_reason = $request->rejection_reason;
        $loan->reviewed_date = now();
        $loan->reviewed_by = Auth::id();
        $loan->save();

        //Send Database Notification
        $notification['icon'] = "ti-file-x-filled";
        $notification['color'] = "danger";
        $notification['heading'] = "Application Declined";
        $notification['msg'] = "Your recent application has been declined.";

        $this->sendNotification($loan->user_id, $notification);
        
        $leadid = LeadMarketBuy::where('loan_application_id',$loan->id)->first();


        if(!$leadid){
            $this->sellLead($id);
        }
        

       

        return redirect()->back()->with('success', 'The loan application has been successfully declined.');


    }
    public function sellLead($loanapplication)
    {
        try {
            $loanApplication = LoanApplication::find($loanapplication);
            $xml = $this->generateXmlPayload($loanApplication);

            $curl = curl_init();

            curl_setopt_array(
                $curl,
                array(
                    CURLOPT_URL => $this->apiEndpoint . '/' . $this->apiSellerKey . '/application/single',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => array(
                        'Settings' => json_encode(
                            array(
                                'API_Token' => $this->apiSellerToken,
                               // 'Test_Sale_Outcome' => '1',
                                'Client_ID' => $this->sellerClientID
                            )
                        ),
                        'Payload' => $xml
                    ),
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: multipart/form-data',
                        'Accept: application/json'
                    ),
                )
            );

            $response = curl_exec($curl);
            $result = json_decode($response, true);
            $status = $this->getLeadStatus($result['Data']['Lead_ID']);

            $this->storeLead($loanApplication, $result, $status);

            curl_close($curl);

            return;



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

    public function storeLead($loanApplication, $result, $status)
    {

        $lead = LeadMarket::where('lead_id', $result['Data']['Lead_ID'])->first();

        if(!$lead){
            $lead = new LeadMarket();
        }
        
        $lead->user_id = $loanApplication->user_id;
        $lead->loan_application_id = $loanApplication->id;
        $lead->lead_id = $result['Data']['Lead_ID'];
        $lead->lead_token = $result['Data']['Lead_Token'];
        $lead->status = $status['Data']['Status_Type_Description'];
        $lead->sale_data = json_encode($status['Data']['Sale_Data']);
        $lead->save();

        $this->sendEmailNotification($lead->user_id,  $lead->loan_application_id, $lead->lead_token, 'decline-application');

        return;

    }

    public function getLeadStatus($leadId)
    {

        try {
            $client = new Client();
            $response = $client->post($this->apiEndpoint . '/' . $this->apiSellerKey . '/application/status', [
                'json' => [
                    'API_Token' => $this->apiSellerToken,
                    'Lead_ID' => $leadId,
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);

            // Get the response body as a string
            if ($response->getStatusCode() === 200) {
                // Get the response body
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

    public function view($id)
    {

        $data['lead'] = LeadMarket::find($id);
        $data['breadcrumb'] = $this->breadcrumb('Lead Market', 'Lead ID# '.$data['lead']->lead_id, $this->url, null);

        $status = $this->getLeadStatus($data['lead']->lead_id);
        $data['lead']->status = $status['Data']['Status_Type_Description'];
        $data['lead']->save();
        
        $data['detail'] = $this->getLeadDetails($data['lead']->lead_id);


        return view('admin.leadMarket.detail', $data);


    }
    public function getLeadDetails($leadId)
    {
        try {
            
            $client = new Client();

            // Make a POST request to get lead details
            $response = $client->post($this->apiEndpoint . '/' . $this->apiSellerKey . '/application/detail', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'API_Token' => $this->apiSellerToken,
                    'Lead_ID' => $leadId,
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
    function sendEmailNotification($user_id, $loan_id,$token, $template)
    {

        $data = array(
            'user_id' => $user_id,
            'template' => $template,
            'type' => 'mail',
            'loan_application_id' => $loan_id,
            'url' => 'app.cashfaster.com.au/customer/leadmarket/'.$token
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


}
