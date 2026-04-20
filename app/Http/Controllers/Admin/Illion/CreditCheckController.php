<?php

namespace App\Http\Controllers\Admin\Illion;

use App\Http\Controllers\Controller;
use App\Models\IllionCost;
use App\Models\IllionCreditCheck;
use App\Models\LoanApplication;
use App\Traits\GeneralTrait;
use App\Traits\IllionTrait;
use App\Traits\LoanTrait;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CreditCheckController extends Controller
{
    use IllionTrait, GeneralTrait, LoanTrait;

    private function manageArray($loanApplication)
    {
        $user = $loanApplication->user;

        return [
            'CreditCheckRequest' => [
                'UserKey' => ['Value' => env('ILLION_USERNAME')],
                'Password' => ['Value' => env('ILLION_PASSWORD')],
                'SubscriberId' => ['Value' => env('ILLION_SUBSCRIBER_ID')],
                'Version' => ['Value' => '1'],
                'Environment' => ['Value' => env('ILLION_ENVIRONMENT')],
                'RequestReference' => ['Value' => $this->generateUniqueRefNumber()],
                'Enquiry' => [
                    [
                        'ProductCode' => ['Value' => 'XNEQV2'],
                        'Country' => ['Value' => 'AU'],
                        'Amount' => ['Value' => $loanApplication->amount],
                        'EnquiryType' => ['Value' => '1'],
                        'AccountType' => ['Value' => '005'],
                        'CreditObligation' => ['Value' => '005'],
                        'IndividualDetails' => [
                            'PersonName' => [
                                [
                                    'FirstName' => ['Value' => $user->first_name],
                                    'OtherName' => ['Value' => $user->middle_name],
                                    'Surname' => ['Value' => $user->last_name]
                                ]
                            ],
                            'DateOfBirth' => ['Value' => date('Y-m-d', strtotime($user->dob))],
                            'Gender' => ['Value' => 'U'],
                            'Address' => [
                                [
                                    'UnitNumber' => ['Value' => '1'],
                                    'StreetNumber' => ['Value' => $this->getUserAttrValue($loanApplication->id, 'street_number')],
                                    'StreetName' => ['Value' => $this->getUserAttrValue($loanApplication->id, 'route')],
                                    'StreetType' => ['Value' => $this->getUserAttrValue($loanApplication->id, 'administrative_area_level_2')],
                                    'SuburbTown' => ['Value' => $this->getUserAttrValue($loanApplication->id, 'locality')],
                                    'State' => ['Value' => $this->getUserAttrValue($loanApplication->id, 'administrative_area_level_1')],
                                    'Postcode' => ['Value' => '4215'],
                                    'Country' => ['Value' => 'AU']
                                ]
                            ],
                            'UniqueCustomerReference' => ['Value' => $this->generateUniqueUserRefNumber(3)]
                        ]
                    ]
                ]
            ],
            'ReportOption' => [
                'ReportFormat' => 2
            ]
        ];
    }

    public function doCreditCheck($application_id)
    {
        $client = new Client();

        try {
            $loanApplication = LoanApplication::findOrFail($application_id);


            $response = $client->post(env('ILLION_CREDIT_CHECK_URL'), [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $this->manageArray($loanApplication)
            ]);

            $responseArray = json_decode($response->getBody()->getContents());

            $this->store($responseArray, $loanApplication);

            return redirect()->back()->with('success', 'Illion Consumer Risk Score generated successfully!');

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $errorResponse = json_decode($e->getResponse()->getBody()->getContents(), true);
                $statusCode = $e->getResponse()->getStatusCode();
            } else {
                $errorResponse = ['message' => $e->getMessage()];
                $statusCode = 500;
            }
            return redirect()->back()->with('error', 'An error occurred: ' . $errorResponse);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function savePdf($pdfData)
    {
        $fileName = 'credit_report_' . time() . '.pdf';
        Storage::put('public/bureau/' . $fileName, $pdfData);
        return $fileName;
    }

    public function store($responseArray, $loanApplication)
    {
        //Store File
        $pdfData = base64_decode($responseArray->ReportsData[0]->Base64EncodedData);
        $filename = $this->savePdf($pdfData);

        $CreditReportResponse = $responseArray->CreditCheckResponse->CreditReportResponse[0];


        $fullname = collect([
            $CreditReportResponse->CreditReport->ConsumerSummary->PersonName->FirstName->Value ?? '',
            $CreditReportResponse->CreditReport->ConsumerSummary->PersonName->OtherName->Value ?? '',
            $CreditReportResponse->CreditReport->ConsumerSummary->PersonName->Surname->Value ?? ''
        ])
            ->filter()
            ->implode(' ');

        //Insert Data
        $creditCheck = IllionCreditCheck::firstOrNew([
            'application_id' => $loanApplication->id,
        ]);

        $creditCheck->user_id = $loanApplication->user_id;
        $creditCheck->consumer_id = $CreditReportResponse->CreditReport->ConsumerSummary->ConsumerId->Value;
        $creditCheck->unique_customer_reference = $CreditReportResponse->RequestSummary->UniqueCustomerReference->Value;
        $creditCheck->fullname = $fullname;
        $creditCheck->score = $CreditReportResponse->CreditReport->Scores->Score[0]->Score->Value ?? 0;
        $creditCheck->credit_report = json_encode($CreditReportResponse->CreditReport);
        $creditCheck->filename = $filename;
        $creditCheck->save();


        //Insert Cost
        $this->illionCost($creditCheck);


        return;

    }

    function illionCost($creditCheck)
    {
        $existingCost = IllionCost::where('loanapplication_id', $creditCheck->application_id)->where('type','Credit Check')->first();
        $amount = $existingCost ? 0.60 : 3.50;

        $cost = new IllionCost();
        $cost->loanapplication_id = $creditCheck->application_id;
        $cost->user_id = $creditCheck->user_id;
        $cost->customerId = $creditCheck->consumer_id;
        $cost->amount = $amount;
        $cost->type = 'Credit Check';
        $cost->save();

        return;
    }
}
