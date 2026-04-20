<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContractSigning;
use App\Models\LoanApplication;
use App\Traits\GeneralTrait;
use App\Traits\LoanTrait;
use App\Traits\MessageTrait;
use App\Traits\ReportTrait;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;

class ContractSignController extends Controller
{
    use GeneralTrait, LoanTrait, ReportTrait, MessageTrait;
    public function index(Request $request, $id)
    {
        $loanapplication = LoanApplication::find($id);
        $user = $loanapplication->user;

        $client = new Client();

        $token = $this->getToken($loanapplication);
        $filename = "CashFasterAUCredit#" . $id;
        try {
            $response = $client->request('POST', env('PANDADOC_API_URL') . 'documents', [
                'headers' => [
                    'Authorization' => 'API-Key ' . env('PANDADOC_API_KEY'),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    "name" => $filename,
                    "template_uuid" => env('PANDADOC_TEMPLATE_ID'),
                    "recipients" => [
                        [
                            "email" => 'info@riryglam.com',
                            //"email" => $user->email,
                            "first_name" => $user->first_name,
                            "last_name" => $user->last_name,
                            "role" => "Contractor"
                        ],
                        [
                            "email" => 'test@riryglam.com',
                            //"email" => $user->email,
                            "first_name" => 'Cash',
                            "last_name" => 'Faster',
                            "role" => "Cashfaster"
                        ]
                    ],
                    "tokens" => $token,

                    "metadata" => [
                        "my_favorite_pet" => "Panda"
                    ],
                    "tags" => [
                        "created_via_api",
                        "test_document"
                    ]
                ],
            ]);

            // Get the response body
            $body = $response->getBody()->getContents();
            $response = json_decode($body);


            $status = $this->getStatus($response->id);

            $contractSend = $this->sendDocument($response->id);

            $document = new ContractSigning();
            $document->application_id = $id;
            $document->user_id = $loanapplication->user_id;
            $document->filename = $filename;
            $document->pandoc_id = $response->id;
            $document->status = $contractSend->status ?? 'sent';
            $document->info_message = $response->info_message;
            $document->shared_link = $contractSend->recipients[1]->shared_link ?? '#';
            $document->shared_link_cf = $contractSend->recipients[0]->shared_link  ?? '#';
            $document->save();


            //Send Email Notification
            $datas = array(
                'user_id' => $user->id,
                'template' => 'contract-signing',
                'type' => 'mail',
                'link' => $contractSend->recipients[1]->shared_link,
                'loan_application_id' => $id,
            );
            $this->storeMsg($datas);

            //Send Database Notification
            $notification['icon'] = "ti-mail";
            $notification['color'] = "warning";
            $notification['heading'] = "Please Review & Sign your CashFaster Loan Contract";
            $notification['msg'] = "Please check your email, and sign your contract";

            $this->sendNotification($loanapplication->user_id, $notification);


            $this->logActivity('Pre Approval of Application ID #' . $id, null);




            return redirect()->back()->with('success', 'Contract signing document sent successfully');


        } catch (\Exception $e) {

            echo 'Error: ' . $e->getMessage();
        }

    }

    public function getToken($data)
    {

        $loanAmount = $data->approved_amount;
        $loanDuration = $data->duration;
        $loanFrequency = $data->frequency;
        $purpose = $this->getUserAttrValue($data->id, 'reason_for_loan');

        $loan = $this->getStatement($loanAmount, $loanDuration, $loanFrequency);



        $token_array =
            array(
                array(
                    "name" => "Applicant.Name",
                    "value" => $this->getUserName($data->user_id)
                ),
                array(
                    "name" => "Contract.Date",
                    "value" => $this->formateDate(now())
                ),
                array(
                    "name" => "Loan.Amount",
                    "value" => $this->formateNumber($loanAmount)
                ),
                array(
                    "name" => "Loan.Establishment.Fee",
                    "value" => $this->formateNumber($loan['establishmentFee'])
                ),
                array(
                    "name" => "Loan.Government.Fee",
                    "value" => "0.00"
                ),
                array(
                    "name" => "Loan.Interest.Fee",
                    "value" => $this->formateNumber($loan['montlyintrest'])
                ),
                array(
                    "name" => "Loan.Purpose",
                    "value" => $purpose
                ),
                array(
                    "name" => "Loan.Repayment.Amount",
                    "value" => $this->formateNumber($loan['repayment_amount'])
                ),
                array(
                    "name" => "Loan.Repayment.Number",
                    "value" => $loan['repayment']
                ),
                array(
                    "name" => "Loan.Repayment.Total",
                    "value" => $this->formateNumber($loan['repayment_amount']),
                ),
                array(
                    "name" => "Loan.Term",
                    "value" => $loan['repayment']
                ),
                array(
                    "name" => "Loan.Total.Fee",
                    "value" => $this->formateNumber($loan['totalFeeMontly']),
                ),
                array(
                    "name" => "Payable.Settlement",
                    "value" => $this->formateNumber($loan['payabl_settlement']),
                ),
                array(
                    "name" => "Payable.Throughout",
                    "value" => $this->formateNumber($loan['totalFee'])
                )
            );

        return json_encode($token_array);
    }

    public function checkStatus($id)
    {
        $status = $this->getStatus($id);

        $document = ContractSigning::where('pandoc_id', $id)->first();
        $document->status = $status;
        $document->save();

        return redirect()->back()->with('success', 'Contract signing document status updated successfully');
    }
    public function getStatus($id)
    {
        $client = new Client();

        try {
            $response = $client->request('GET', env('PANDADOC_API_URL') . 'documents/' . $id, [
                'headers' => [
                    'Authorization' => 'API-Key ' . env('PANDADOC_API_KEY'),
                ],
            ]);

            $body = $response->getBody()->getContents();
            $response = json_decode($body);

            return $response->status;


        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
    public function download($id)
    {
        $client = new Client();

        try {

            $contact = ContractSigning::where('pandoc_id', $id)->first();
            $filename = $contact->filename;


            $response = $client->request('GET', env('PANDADOC_API_URL') . 'documents/' . $id . '/download', [
                'headers' => [
                    'Authorization' => 'API-Key ' . env('PANDADOC_API_KEY'),
                ],
                'stream' => true,
            ]);

            // Set appropriate headers for file download
            header('Content-Type: ' . $response->getHeader('Content-Type')[0]);
            header('Content-Disposition: attachment; filename="' . $filename . '.pdf"');
            echo $response->getBody();
            // Output the file content directly to the browser


        } catch (RequestException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    public function sendDocument($id)
    {
        $client = new Client();
        try {
            $response = $client->post(env('PANDADOC_API_URL') . 'documents/' . $id . '/send', [
                'headers' => [
                    'Authorization' => 'API-Key ' . env('PANDADOC_API_KEY'),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'message' => 'Hello! This document was sent from the PandaDoc API.',
                    'silent' => true,
                ],
            ]);


            $body = $response->getBody()->getContents();
            $response = json_decode($body);

            return $response;

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                // echo "Error: " . $e->getResponse()->getStatusCode() . "\n";
                return $e->getResponse()->getBody() . "\n";
            } else {
                return "Error: " . $e->getMessage() . "\n";
            }
        }
    }

}
