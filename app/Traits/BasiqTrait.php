<?php



namespace App\Traits;



use App\Models\User;

use GuzzleHttp\Client;

use Illuminate\Support\Facades\Log;

use GuzzleHttp\Exception\RequestException;



trait BasiqTrait

{

    private $access_token = null;

    private $basiq_user_id = null;



    public function checkBalance($basiq_user_id)

    {

        try {
            $this->basiq_user_id = $basiq_user_id;

            $user = User::where('basiq_user_id', $basiq_user_id)->first();

            if ($this->authenticate()) {

                $accounts = $this->accountList();

                return $accounts;

            }

            return false;

        } catch (RequestException $e) {

            $response = $e->getResponse();

            $reasonPhrase = $response->getReasonPhrase();

            Log::error("Error BasiqTrait checkbalnce : $reasonPhrase");

            throw $e;

        }



    }

    public function authenticate()

    {

        $client = new Client();



        try {

            $response = $client->post(env('BASIQ_API_URL') . 'token', [

                'headers' => [

                    'Authorization' => 'Basic ' . env('BASIQ_API_KEY'),

                    'Content-Type' => 'application/x-www-form-urlencoded',

                    'basiq-version' => '2.0',

                ],

            ]);



            $data = json_decode($response->getBody()->getContents(), true);

            $this->access_token = $data['access_token'];

            return true;



        } catch (RequestException $e) {

            $response = $e->getResponse();

            $reasonPhrase = $response->getReasonPhrase();

            Log::error("Error BasiqTrait authenticate : $reasonPhrase");

            throw $e;

        }

    }



    public function accountList()

    {

        $client = new Client();



        try {

            $response = $client->get(env('BASIQ_API_URL') . 'users/' . $this->basiq_user_id . '/accounts/', [

                'headers' => [

                    'Authorization' => 'Bearer ' . $this->access_token,

                    'Accept' => 'application/json',

                    'Content-Type' => 'application/json',

                ],

            ]);



            $responseData = json_decode($response->getBody()->getContents(), true);



            return $responseData['data'];



        } catch (\Exception $e) {



            dd($e->getMessage());

            return ['success' => false, 'message' => $e->getMessage()];

        }

    }





}