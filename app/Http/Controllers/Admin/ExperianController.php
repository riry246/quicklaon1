<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CreditScore;
use App\Models\LoanApplication;
use App\Traits\GeneralTrait;
use App\Traits\LoanTrait;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ExperianController extends Controller
{
    use LoanTrait, GeneralTrait;
    private $access_token = null;
    private $refresh_token = null;
    private $app_id = null;

    private $module_name = 'Credit Score';
    private $module = 'credit';
    private $url = 'loan.index';


    public function index($application_id)
    {
        $data['breadcrumb'] = $this->breadcrumb('Credit Score', 'Detail', $this->url, null);

        $loanApplication = LoanApplication::find($application_id);
        $creditScore = $loanApplication->latestcreditScore;
        $data['summary'] = json_decode($creditScore->response);

        return view('admin.creditScore.creditScore', $data);

    }
    public function getToken($application_id)
    {
        try {
            $this->app_id = $application_id;

            $jsonData = $this->manageArray();

            $client = new Client();
            $response = $client->request('POST', env('EXPERIAN_URL') . 'oauth2/v1/token', [
                'form_params' => [
                    'username' => env('EXPERIAN_USERNAME'),
                    'password' => env('EXPERIAN_PASSWORD'),
                    'client_id' => env('EXPERIAN_CLIENT_ID'),
                    'client_secret' => env('EXPERIAN_CLIENT_SECRET'),
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-type' => 'application/x-www-form-urlencoded',
                ],
            ]);

            $resp = json_decode($response->getBody()->getContents());
            
            $this->access_token = $resp->access_token;
            $this->refresh_token = $resp->refresh_token;

            $res = json_decode($this->getExperianData());

            $creditScore = new CreditScore();
            $creditScore->application_id = $application_id;

            if (isset($res->MODEL0001[0]->ScoreValue)) {
                $creditScore->score_value = $res->MODEL0001[0]->ScoreValue;
            } else {
                $creditScore->score_value = 0;
            }


            if (isset($res->MODEL0001[0]->ScoreCardNum)) {
                $creditScore->score_card_num = $res->MODEL0001[0]->ScoreCardNum;
            } else {
                $creditScore->score_card_num = 0;
            }


            $creditScore->system_message_description = $res->SYSMESSG->systemMessageDescription;
            $creditScore->system_message_code = $res->SYSMESSG->systemMessageCode;
            $creditScore->response = json_encode($res);
            $creditScore->save();


            return redirect()->back()->with('success', 'Credit Score generated successfully!');
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $reasonPhrase = $response->getReasonPhrase();
            } else {
                $reasonPhrase = 'Oops! something went worng';
            }

            return redirect()->back()->with('error', $reasonPhrase);
        }
    }

    public function updateToken(Request $request)
    {
        try {
            $this->app_id = $request->input('id');

            $jsonData = $this->manageArray();

            $client = new Client();
            $response = $client->request('POST', env('EXPERIAN_URL') . 'oauth2/v1/token', [
                'form_params' => [
                    'username' => env('EXPERIAN_USERNAME'),
                    'password' => env('EXPERIAN_PASSWORD'),
                    'client_id' => env('EXPERIAN_CLIENT_ID'),
                    'client_secret' => env('EXPERIAN_CLIENT_SECRET'),
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-type' => 'application/x-www-form-urlencoded',
                ],
            ]);

            $resp = json_decode($response->getBody()->getContents());

            $this->access_token = $resp->access_token;
            $this->refresh_token = $resp->refresh_token;

            $res = json_decode($this->getExperianData());

            $creditScore = new CreditScore();
            $creditScore->application_id = $this->app_id;

            if (isset($res->MODEL0001[0]->ScoreValue)) {
                $creditScore->score_value = $res->MODEL0001[0]->ScoreValue;
            } else {
                $creditScore->score_value = 0;
            }


            if (isset($res->MODEL0001[0]->ScoreCardNum)) {
                $creditScore->score_card_num = $res->MODEL0001[0]->ScoreCardNum;
            } else {
                $creditScore->score_card_num = 0;
            }


            $creditScore->system_message_description = $res->SYSMESSG->systemMessageDescription;
            $creditScore->system_message_code = $res->SYSMESSG->systemMessageCode;
            $creditScore->response = json_encode($res);
            $creditScore->save();

            return response()->json(['success' => 'Credit Score generated successfully!'], 200);
            
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $reasonPhrase = $response->getReasonPhrase();
                return response()->json(['error' =>$reasonPhrase], 500);
            } else {
                return response()->json(['error' => 'Failed to authenticate. Please check your credentials and try again.'], 500);
            }

        }
    }

    public function getExperianData()
    {
        try {
            $jsonData = $this->manageArray();

            $client = new Client();

            $response = $client->post(env('EXPERIAN_URL') . 'cs-nextgen-cr/credit-report/v4', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode($jsonData)
            ]);

            return $response->getBody()->getContents();

        } catch (RequestException $e) {

            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $reasonPhrase = $response->getReasonPhrase();
                $fullErrorMessage = 'HTTP ' . $statusCode . ' ' . $reasonPhrase . ': ' . $response->getBody()->getContents();

            }

            throw $e;
        }
    }

    function manageArray()
    {

        $loanApplication = LoanApplication::findOrFail($this->app_id);
        $user = $loanApplication->user;


        $jsonData = array(
            'ENQHEADER' => array(
                'ClientEnquiryRefNumber' => 'CFClientRef#' . $user->id,
                'BureauMemberID' => '11069',
                'Purpose' => '1',
                'Product' => 'ANCCR1010',
                'EnquiryApplicationType' => 'Y',
                'EnquiryAccountType' => 'AL',
                'EnquiryAmount' => $loanApplication->amount,
                'EnquiryTerms' => 'REV',
                'ClientReference1' => null,
                'ClientReference2' => null,
                'EnquiryCreditPurpose' => '1',
                'ConsUIQVersion' => '4',
                'ConsUOFVersion' => '4',
                'ADDLPROD' => array(
                    array(
                        'EnquiryAddOnProduct' => 'CSMSCRN01'
                    )
                )
            ),
            'PRSNSRCH' => array(
                'FirstGivenName' => $user->first_name,
                'MiddleName' => $user->middle_name,
                'OtherMiddleNames' => '',
                'FamilyName' => $user->last_name,
                'Suffix' => $this->getUserAttrValue($loanApplication->id, 'title'),
                'ApplicationRole' => '1',
                'ConsentInd' => 'Y',
                'DayOfBirth' => date('d', strtotime($user->dob)),
                'MonthOfBirth' => date('m', strtotime($user->dob)),
                'YearOfBirth' => date('Y', strtotime($user->dob)),
                'Gender' => '1',
                'PERSALIAS' => array(
                    array(
                        'AliasName' => $user->first_name,
                        'AliasType' => '1'
                    )
                ),
                'PERSONID' => array(
                    array(
                        'IdNumberType' => '1',
                        'IdNumber' => ''
                    )
                ),
                'PERSADDR' => array(
                    array(
                        'AddrType' => '1',
                        'PropertyName' => '',
                        'AddressLine1' => $this->getUserAttrValue($loanApplication->id, 'street_number') . ' ' . $this->getUserAttrValue($loanApplication->id, 'route'),
                        'AddressLine2' => $this->getUserAttrValue($loanApplication->id, 'administrative_area_level_2'),
                        'LocalityName' => $this->getUserAttrValue($loanApplication->id, 'locality'),
                        'State' => $this->getUserAttrValue($loanApplication->id, 'administrative_area_level_1'),
                        'PostalCode' => $this->getUserAttrValue($loanApplication->id, 'postal_code'),
                        'CountryCode' => 'AUS'
                    )
                ),
                'EMPLOYER' => array(
                    'EmployerName' => '',
                )
            )
        );
//dd($jsonData);
        return $jsonData;
    }
}
