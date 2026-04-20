<?php

namespace App\Traits;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Response;
use Spatie\ArrayToXml\ArrayToXml;
use Illuminate\Http\Response as IlluminateResponse;

trait LeadMarketTrait
{
    use LoanTrait;
    public function generateXmlPayload($loanApplication)
    {
        $user = $loanApplication->user;


        $dataArray = [
            'Seller_Data' => [
                'Client_Authority' => 'false',
                'Customer_Authority' => 'false',
                'Customer_Authority_Type' => 'Explicit',
                'Accept_Anti_Hawking' => 'true',
                'Use_MLM_Flow' => 'true',
                'Lead_Reference' => 'CASHFASTER_REF#' . $user->id.'_'.$loanApplication->id,
            ],
            'Application' => [
                'Amount' => $loanApplication->amount,
                'Reason' => $this->getUserAttrValue($loanApplication->id, 'reason_for_loan'),
            ],
            'Applicant' => [
                'Person_Applicant' => [
                    'Title' => $this->getUserAttrValue($loanApplication->id, 'title'),
                    'Date_Of_Birth' => $user->dob,
                    'Last_Name' => $user->last_name,
                    'First_Name' => $user->first_name,
                    'Marital_Status' => '',
                    'Contact_Methods' => [
                        'Contact_Method' => [
                            'Contact_Other' => [
                                'Contact_Type' => 'MOB',
                                'Contact_Value' => $user->mobile,
                            ],
                        ],
                        'Contact_Method_temp_1' => [
                            'Contact_Other' => [
                                'Contact_Type' => 'EMAIL',
                                'Contact_Value' => $user->email,
                            ],
                        ],
                        'Contact_Method_temp_2' => [
                            'Address' => [
                                'Address_Line_1' => $this->getUserAttrValue($loanApplication->id, 'street_number') . ' ' . $this->getUserAttrValue($loanApplication->id, 'route'),
                                'Address_Line_2' => $this->getUserAttrValue($loanApplication->id, 'administrative_area_level_2'),
                                'Suburb' => $this->getUserAttrValue($loanApplication->id, 'locality'),
                                'State' => $this->getUserAttrValue($loanApplication->id, 'administrative_area_level_1'),
                                'Postcode' => $this->getUserAttrValue($loanApplication->id, 'postal_code'),
                                'Is_Current' => 'true',
                                'Residential_Type' => 'false',
                                'Residential_Status' => '1',
                                'Time_At_Address_Months' => '25',
                            ],
                        ],
                    ],
                ],
            ],
        ];



        $xmlContent = ArrayToXml::convert($dataArray, 'Lead', true);
        $xmlContent = str_replace(['_temp_1', '_temp_2'], '', $xmlContent);

        return response($xmlContent, IlluminateResponse::HTTP_OK)
            ->header('Content-Type', 'text/xml')->content();

    }

    

}