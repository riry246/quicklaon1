<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashFasterContract;
use App\Models\ContractSigning;
use App\Models\LoanApplication;
use App\Models\LoanTerm;
use App\Traits\GeneralTrait;
use App\Traits\LoanTrait;
use App\Traits\MessageTrait;
use App\Traits\ReportTrait;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use PDF;

class ContractController extends Controller
{
    use GeneralTrait, LoanTrait, ReportTrait, MessageTrait;
    public function index(Request $request, $id)
    {
        try {
            $loanapplication = LoanApplication::find($id);
            $user = $loanapplication->user;

            //Get the loan Contract Template
            $template = LoanTerm::where('slug', 'cashfasteraucredit')->first();

            //get variables
            $variables = $this->getToken($loanapplication);

            //Create a document
            $document = $this->replaceVariables($template->terms, $variables);

            $ref_code = $this->refCode();

            //get the latest contract number
            $latestContact = CashFasterContract::latest()->first();
            if ($latestContact) {
                $contactid = $latestContact->contract_id;
                $contactid = $contactid + 1;
            } else {
                $contactid = 2056;
            }

            $contract = new CashFasterContract();
            $contract->contract_id = $contactid;
            $contract->application_id = $id;
            $contract->ref_code = $ref_code;
            $contract->status = 'sent';
            $contract->document = $document;
            $contract->share_link_customer = 'contract/' . $ref_code . '/' . $user->basiq_code;
            $contract->share_link_cf = 'admin/contract/' . $ref_code . '/' . $user->basiq_code;
            $contract->save();

            $user->contract_signed = null;
            $user->save();

            //Send Email Notification
            $datas = array(
                'user_id' => $user->id,
                'template' => 'contract-signing',
                'type' => 'mail',
                'link' => $contract->share_link_customer,
                'loan_application_id' => $id,
            );
            $this->storeMsg($datas);

            //send Inapp Notification
            $datas = array(
                'user_id' => $user->id,
                'subject' => 'Contract Signing Request',
                'content' => "You have received a contract signing request! Please review and sign the contract.",
                'type' => 'inapp',
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

        if($loanFrequency == 'weekly'){
            $loan_fre = 'weeks';
        }else{
            $loan_fre = 'fortnight';
        }


        $token_array =
            array(
                "date" => $this->dateFormater(now(), 'jS F, Y'),
                "applicant_name" => $this->getUserName($data->user_id),
                "loan_amount" => $this->formateNumber($loanAmount),
                "loan_purpose" => $purpose,
                "repayment_amount" => $loan['repayment_amount'],
                "repayment_duration" => $loan['repayment'],
                "min_repayment" => $loan['repayment_amount'],
                "establishment_fee" => $loan['establishmentFee'],
                "monthly_account_keeping_fee" => $loan['montlyintrest'],
                "government_fee" => "0.00",
                "total_fees_charges_payable" => $loan['totalFeeMontly'],
                "payable_after_settlement_date" => $loan['payabl_settlement'],
                "payable_throughout" => $loan['totalFee'],
                "loanapplication_id" => $data->id,
                "loan_frequency" => $loanFrequency,
                "loan_fre" => $loan_fre,
                "user_id" => $data->user_id,


            );

        return $token_array;
    }



    public function download($id)
    {
        $contract = CashFasterContract::where('ref_code', $id)->first();

        $loanApplication = LoanApplication::find($contract->application_id);
        $borrower = $loanApplication->user;


        if ($contract->signature_type_customer == 'draw') {
            $signature_customer = '<img src="' . $contract->signature_customer . '" width="150"/>';
        } else {

            $signature_customer = '<div class="signature">' . $contract->signature_customer . '</div>';
        }

        if ($contract->signature_type_cf == 'draw') {
            $signature_cf = '<img src="' . $contract->signature_cf . '" width="150"/>';
        } else {
            $signature_cf = '<div class="signature">' . $contract->contract_id . '</div>';
        }

        $data = [
            'signature_cf' => $signature_cf,
            'signature_date_cf' => $contract->signed_date_cf,
            'signature_customer' => $signature_customer,
            'signature_date_customer' => $contract->signed_date_customer,
            'signature_day' => $this->dateFormater($contract->signed_date_customer, 'l'),
            'signature_month_year' => $this->dateFormater($contract->signed_date_customer, 'jS M,Y'),
            'contract' => $contract,
            'borrower' => $borrower
        ];
        // dd($data);
        $document = $this->replaceVariables($contract->document, $data);
        $data['document'] = $document;
        //  return view('admin.contract.pdf', $data);


        $pdf = PDF::loadView('admin.contract.pdf', $data);

        $filename = 'CashFasterAUCredit#' . $contract->contract_id . '.pdf';

        $contract->filename = $filename;
        $contract->save();

        // Save the PDF file to the storage path
        $pdf->save(storage_path('app/public/contracts/' . $filename));

        // Stream the PDF as the response
        return $pdf->stream($filename);
    }
}
