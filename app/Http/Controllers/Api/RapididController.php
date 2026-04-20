<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserIdVerification;
use App\Traits\GeneralTrait;
use App\Traits\MessageTrait;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class RapididController extends Controller
{
    use GeneralTrait, MessageTrait;
    private $type = null;

    public function verify(Request $request)
    {
        $value = $request->input('value');
        $this->type = $value['type'];

        if ($this->type == 'passport') {
            $numb = $value['TravelDocumentNumber'];
        } elseif ($this->type == 'driverLicence') {
            $numb = $value['LicenceNumber'];
        } else {
            $numb = $value['CardNumber'];
        }

        $count = UserIdVerification::where('user_id', $request->input('user_id'))->get();

        if (count($count) >= 3) {
            return response()->json(['success' => false, 'error_message' => 'Too many attempt. Contact cashfaster team for support', 'message' => 'Too many attempt. Contact cashfaster team for support']);
        }



        $form_param = $this->manageArray($value);

        $client = new Client();

        try {


            //Check ID

            $checkID = UserIdVerification::where('id_number', $numb)
                ->where('id_type', $this->type)
                ->where('status', 'verified')
                ->first();

            if ($checkID) {

                $user = User::findOrFail($request->input('user_id'));
                $risk_flag = 2;
                $user->risk_flag = $risk_flag;
                $user->save();


                return response()->json([
                    'success' => false,
                    'error_message' => 'ID already exists',
                    'error' => [
                        'message' => 'ID already exists',
                        'code' => '455',

                    ],
                ]);
            }


            $client = new Client();

            $response = $client->post('https://' . env('RAPID_ENVIRONMENT') . '.ridx.io/dvs/v1/' . $this->type, [
                'headers' => [
                    'token' => env('RAPID_API_KEY'),
                    'Content-Type' => 'application/json',
                ],
                'json' => $form_param
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            $userID = new UserIdVerification();
            $userID->user_id = $request->input('user_id');
            $userID->id_number = $numb;
            $userID->id_type = $this->type;
            $userID->id_information = json_encode($form_param);
            $userID->api_response = json_encode($data);

            if ($data) {
                $user = User::findorfail($request->input('user_id'));

                if ($data['VerifyDocumentResult']['VerificationResultCode'] == 'Y') {
                    $userID->verified_date = now();
                    $userID->status = 'verified';
                    $userID->save();

                    //update user 
                    $user->id_verified = true;
                    $user->save();

                    //send notification
                    $this->notifiaction($user);

                    $this->addScore($userID->user_id, 'id-verification');

                    return response()->json(['success' => true, 'message' => $data]);
                } else {
                    $userID->status = 'rejected';
                    $userID->save();

                    //send notification
                    //  $this->notifiaction($user);

                    return response()->json(['success' => false, 'error_message' => 'ID Verification Failed: Please check and re-submit information.', 'message' => $data]);
                }

            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error_message' => 'Opps! something went wrong.',
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'trace' => $e->getTrace(),
                ],
            ]);
        }
    }

    public function manageArray($data)
    {
        if ($this->type == 'passport') {
            $formParams = [
                'GivenName' => $data['GivenName'],
                'FamilyName' => $data['FamilyName'],
                'TravelDocumentNumber' => $data['TravelDocumentNumber'],
                'BirthDate' => date('Y-m-d', strtotime($data['BirthDate'])),
                'Gender' => ucfirst($data['Gender']),
            ];
        } elseif ($this->type == 'centerlink') {
            $formParams = [
                'BirthDate' => $data['BirthDate'],
                'Name' => $data['Name'],
                'CardType' => $data['CardType'],
                'CardExpiry' => $data['CardExpiry'],
                'CustomerReferenceNumber' => $data['CustomerReferenceNumber'],
            ];
        } elseif ($this->type == 'citizenship') {
            $formParams = [
                'BirthDate' => $data['BirthDate'],
                'GivenName' => $data['GivenName'],
                'MiddleName' => $data['MiddleName'],
                'FamilyName' => $data['FamilyName'],
                'StockNumber' => $data['StockNumber'],
                'AcquisitionDate' => $data['AcquisitionDate'],
            ];
        } elseif ($this->type == 'driverLicence') {
            $formParams = [
                'BirthDate' => date('Y-m-d', strtotime($data['BirthDate'])),
                'GivenName' => $data['GivenName'],
                'MiddleName' => $data['MiddleName'],
                'FamilyName' => $data['FamilyName'],
                'LicenceNumber' => $data['LicenceNumber'],
                'CardNumber' => $data['CardNumber'],
                'StateOfIssue' => $data['StateOfIssue'],
            ];
        } elseif ($this->type == 'medicare') {
            $formParams = [
                'BirthDate' => date('Y-m-d', strtotime($data['BirthDate'])),
                'FullName1' => $data['FullName1'],
                'CardNumber' => $data['CardNumber'],
                'CardType' => $data['CardType'],
                'IndividualReferenceNumber' => (int) $data['IndividualReferenceNumber'],

            ];
            if ($data['CardType'] == 'G') {
                $formParams['CardExpiry'] = date('Y-m', strtotime($data['CardExpiry']));
            } else {
                $formParams['CardExpiry'] = date('Y-m-d', strtotime($data['CardExpiry']));
            }
            $i = 2;
            foreach ($data['additionalFullNames'] as $k => $v) {
                $formParams['FullName' . $i++] = $v;
            }
        }

        return $formParams;
    }

    public function notifiaction($user)
    {
        $admin = $this->listAdmin();

        foreach ($admin as $a) {
            $data = array(
                'user_id' => $a->id,
                'template' => 'id-verification-submission-notification-admin',
                'type' => 'mail',
                'client_name' => $user->first_name . ' ' . $user->last_name,
                'idtype' => $this->type,
                'subbmission_date' => $this->formateDate(now()),
            );

            $this->storeMsg($data);
        }

        return;
    }

}