<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\User;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class BasiqController extends Controller
{
    private $access_token = null;
    private $basiq_user_id = null;
    private $institution = null;
    private $loginId = null;
    private $password = null;
    private $user_id = null;
    private $application_id = null;
    private $bank_account_id = null;
    private $accounts = null;

    public function authenticate(Request $request)
    {
        set_time_limit(120);
        $this->institution = $request->input('institution');
        $this->loginId = $request->input('loginId');
        $this->password = $request->input('password');
        $this->application_id = $request->input('app_id');

        $client = new Client();

        try {
            $response = $client->post('https://au-api.basiq.io/token', [
                'headers' => [
                    'Authorization' => 'Basic ' . env('BASIQ_API_KEY'),
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'basiq-version' => '2.0',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            $this->access_token = $data['access_token'];
            //dd($this->access_token);
            $mobile = $request->input('mobile');

            //Check for Basiq user_id
            $user = User::where('mobile', $mobile)->first();

            $this->basiq_user_id = $user->basiq_user_id;

            $this->createUser($mobile);
            $user = User::findOrFail($user->id);
            $user->basiq_user_id = $this->basiq_user_id;
            $user->save();


            $this->user_id = $user->id;


            //connect bank account
            $this->connectBank();
            sleep(60);
            $this->getAccountDetails();

            $filteredData = array_filter($this->accounts, function ($item) {
                return strpos($item['accountNo'], '*') === false;
            });

            // Re-index the array if needed
            $filteredData = array_values($filteredData);

            return response()->json(['success' => true, 'accounts' => $filteredData]);

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $reasonPhrase = $response->getReasonPhrase();
                return response()->json(['success' => false, 'message' => $reasonPhrase], 401);
            } else {
                return response()->json(['success' => false, 'message' => 'Request error. Please try again later.'], 401);
            }
        }

    }

    public function createUser($mobile)
    {
        $client = new Client();

        try {
            $response = $client->post('https://au-api.basiq.io/users/' . $this->basiq_user_id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'mobile' => '+61' . $mobile,
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);
            $this->basiq_user_id = $responseData['id'];

            return ['success' => true];
        } catch (RequestException $e) {

            $response = $e->getResponse();
            $reasonPhrase = $response->getReasonPhrase();
            throw $e;
        }
    }

    public function connectBank()
    {
        $client = new Client();

        try {
            $response = $client->post('https://au-api.basiq.io/users/' . $this->basiq_user_id . '/connections/', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'institution' => [
                        'id' => $this->institution
                    ],
                    'loginId' => $this->loginId,
                    'password' => $this->password
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            return ['success' => true];
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $reasonPhrase = $response->getReasonPhrase();
            throw $e;
        }


    }
    public function getAccountDetails()
    {
        // Create a new Guzzle HTTP client
        $client = new Client();

        try {
            $response = $client->get('https://au-api.basiq.io/users/' . $this->basiq_user_id . '/accounts', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                    'Accept' => 'application/json',
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            $this->accounts = $responseData['data'];


            if ($responseData) {
                $bankAccount = new BankAccount;
                $bankAccount->user_id = $this->user_id;
                $bankAccount->application_id = $this->application_id;
                $bankAccount->basiq_code = $this->institution;
                //$bankAccount->username = $this->loginId;
                //$bankAccount->password = $this->password;
                $bankAccount->verified_at = now();
                $bankAccount->statements = json_encode($this->accounts);
                // $bankAccount->save();
                $this->bank_account_id = $bankAccount->id;
            }

            return response()->json($responseData);
        } catch (RequestException $e) {

            $response = $e->getResponse();
            $reasonPhrase = $response->getReasonPhrase();
            throw $e;
        }
    }
}