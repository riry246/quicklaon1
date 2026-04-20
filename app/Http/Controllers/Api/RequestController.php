<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CashFasterContract;
use App\Models\Documentation;
use App\Models\FcmToken;
use App\Models\LoanApplication;
use App\Models\Notification;
use App\Models\Referral;
use App\Models\ReferralHistory;
use App\Models\User;
use App\Traits\GeneralTrait;
use App\Traits\MessageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\VerificationEmail;
use Illuminate\Support\Facades\Validator;
use PDF;

class RequestController extends Controller
{
    use GeneralTrait, MessageTrait;
    public function sendEmailVerification(Request $request)
    {
        $user_id = $request->input('user_id');
        $user = User::findorfail($user_id);
        $user->email_verified_at = null;
        $user->email_verification_code = Str::random(64);

        if ($user->save()) {
            Mail::to($user->email)->send(new VerificationEmail($user));
        }

        return response()->json([
            "success" => true,
            "message" => "Verification Code sent",
        ], 200);
    }

    public function documentUpload(Request $request)
    {
        $user_id = $request->input('user_id');
        $app_id = $request->input('app_id');
        $mode = $request->input('mode') ?? null;
        $files = $request->file();

        if ($files) {
            foreach ($files as $k => $v) {
                $image = $request->file($k);
                $imageName = time() . '-' . $k . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/document/', $imageName);
                //$arr[]=$imageName;
                $document = Documentation::where('loan_application_id', $app_id)->where('status', 'sent')->where('document_type', $k)->first();
                $document->filename = $imageName;
                $document->status = 'uploaded';
                $document->save();
            }
        }

        $user = User::findorfail($user_id);
        $user->document_verified = true;
        $user->save();

        //Send email notification
        $admin = $this->listAdmin();

        foreach ($admin as $a) {
            $data = array(
                'user_id' => $a->id,
                'template' => 'loan-document-submission-notification-admin',
                'type' => 'mail',
                'client_name' => $user->first_name . ' ' . $user->last_name,
                'loan_application_id' => $app_id,
            );

            $this->storeMsg($data);
        }



        if ($mode) {
            return redirect()->back()->with('success', 'Document uploaded successfully');
        } else {
            return response()->json([
                "success" => true,
                "message" => "Document uplaoded Succesfully",
            ], 200);
        }

    }

    public function referral(Request $request)
    {
        try {

            $method = $request->input('method');
            $mobile = $request->input('mobile');
            $email = $request->input('email');

            if ($method == 'sms') {
                $isvalidnumber = $this->checkAustralianMobileNumber($mobile);

                if (!$isvalidnumber) {
                    return response()->json(['message' => 'Please enter valid Australian mobile number'], 429);
                }

            } else {
                $validator = Validator::make($request->all(), [
                    'email' => 'required|email',
                ]);

                // Check if the validation fails
                if ($validator->fails()) {
                    return response()->json(['message' => 'The email field must be a valid email address'], 429);
                }

            }


            $user = User::findorfail($request->input('user_id'));
            $referral_code = $user->referral;

            // Recording messages
            $message = array(
                'user_id' => $request->user_id,
                'type' => $request->method,
                'subject' => 'Friend Referral',
                'content' => "I've been using Cashfaster for quick and easy cash solutions. 💸 If you sign up using my referral link https://www.cashfaster.com.au/referral?id=" . $referral_code->code . ", we both benefit! Check it out ",
            );

            $this->storeMsg($message);


            //Store 
            $referral = new ReferralHistory();
            $referral->referral_id = $referral_code->id;
            $referral->method = $request->input('method');
            $referral->mobile = $request->input('mobile');
            $referral->email = $request->input('email');
            $referral->save();

            return response()->json([
                "success" => true,
                "message" => "Great news! 🎉 Your referral request has been sent successfully. Thank you for recommending your friend! If they join, you both stand to benefit.",
            ], 200);

        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'Oops! 😓 It seems there was an issue processing your referral request'], 500);
        }

    }

    public function validateReferalCode(Request $request)
    {
        try {

            $code = $request->input('code');

            $referral = Referral::where('code', $code)->first();

            if ($referral) {
                return response()->json([
                    "success" => true,
                    "message" => "Referral code is valid.",
                ], 200);
            }

            return response()->json(['message' => 'Invalid referral code. Please check and try again.'], 429);

        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'An error occurred.'], 500);
        }

    }

    public function notification(Request $request)
    {
        $user_id = $request->input('user_id');
        $notification = Notification::select('data', 'created_at')->where('notifiable_id', $user_id)->where('read_at', NULL)->get();

        $data = array();
        foreach ($notification as $k => $n) {
            $data[$k]['created_at'] = $n->created_at;
            $data[$k]['data'] = json_decode($n->data);
            foreach ($data[$k]['data'] as $x => $v) {
                $data[$k][$x] = json_decode($v);
            }
        }


        if ($notification) {
            return response()->json([
                "success" => true,
                "notification" => $data,
            ], 200);
        }

        return response()->json(['message' => 'No new notifiactions'], 429);
    }

    public function markReadnotification(Request $request)
    {
        $user_id = $request->input('user_id');
        $notification = Notification::select('data', 'created_at')->where('notifiable_id', $user_id)->where('read_at', NULL)->get();


        foreach ($notification as $k => $n) {
            $notification->read_at = now();
            $notification->save();
        }

        return response()->json([
            "success" => true,
            "notification" => 'Notification marked as read',
        ], 200);

    }

    public function storeFCM(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            $device_id = $request->input('device_id');
            $platform = $request->input('platform');
            $token = $request->input('token');

            $existToken = FcmToken::where('token', $token)->first();

            if ($existToken) {
                $fcm = $existToken;
            } else {
                $fcm = new FcmToken();

            }

            $fcm->user_id = $user_id;
            $fcm->device_id = $device_id;
            $fcm->platform = $platform;
            $fcm->token = $token;
            $fcm->save();

            return response()->json([
                "success" => true,
                "message" => 'FCM token saved successfully',
            ], 200);

        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => $e], 500);
        }
    }
    public function sendNotification(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            $title = $request->input('title');
            $body = $request->input('body');

            $token = FcmToken::where('user_id', $user_id)->first();
            $device_token = $token->token;

            if ($device_token) {
                $response = $this->sendpushNotification($title, $body, $device_token);

                return response()->json([
                    "success" => true,
                    "message" => $response,
                ], 200);
            }

            return response()->json(['message' => 'No registered device found'], 429);


        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'An error occurred.'], 500);
        }
    }

    public function contractSigning(Request $request)
    {
        try {
            $input = $request->input();
            $ref_code = $input['data']['ref_code'];

            $contract = CashFasterContract::where('ref_code', $ref_code)->first();

            $contract->ip_address_customer = $request->ip();
            $contract->signed_date_customer = now();
            $contract->signature_type_customer = $input['data']['type'];
            $contract->signature_customer = $input['data']['signature_customer'];
            $contract->status = 'customer.signed';
            $contract->save();

            $this->checkContractStatus($ref_code);

            $loan_applicaiton = LoanApplication::find($contract->application_id);
            $user = $loan_applicaiton->user;
            $user->contract_signed = 1;
            $user->save();

            $admin = $this->listAdmin();

            foreach ($admin as $a) {
                $data = array(
                    'user_id' => $a->id,
                    'template' => 'contract-signed-notification-admin',
                    'type' => 'mail',
                    'loan_application_id' => $contract->application_id,
                    'client_name' => $user->first_name . ' ' . $user->last_name,
                    'signed_date' => $this->formateDate(now())
                );

                $this->storeMsg($data);
            }



            return response()->json([
                "success" => true,
                "message" => 'Contract signed',
            ], 200);

        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'An error occurred.'], 500);
        }
    }
    public function contractSigningViewed(Request $request)
    {
        try {
            $message = 'Already viewed';
            $input = $request->input();
            $ref_code = $input['data']['ref_code'];

            $contract = CashFasterContract::where('ref_code', $ref_code)->first();

            if (!$contract->view_date_customer) {
                $contract->ip_address_customer = $request->ip();
                $contract->view_date_customer = now();
                $contract->save();
                $message = 'Contract Viewed';
            }

            return response()->json([
                "success" => true,
                "message" => $message,
            ], 200);

        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'An error occurred.'], 500);
        }
    }

    public function contractAdminSigning(Request $request)
    {
        try {
            $input = $request->input();
            $ref_code = $input['data']['ref_code'];

            $contract = CashFasterContract::where('ref_code', $ref_code)->first();

            $contract->ip_address_cf = $request->ip();
            $contract->signed_date_cf = now();
            $contract->signature_type_cf = $input['data']['type'];
            $contract->signature_cf = $input['data']['signature_customer'];
            $contract->status = 'admin.signed';
            $contract->save();

            $this->checkContractStatus($ref_code);


            $loan_applicaiton = LoanApplication::find($contract->application_id);
            $user = $loan_applicaiton->user;
            $user->contract_signed = 1;
            $user->save();

            $file = $this->download($ref_code);

            $data = array(
                'user_id' => $user->id,
                'template' => 'loan-application-contract-is-signed',
                'type' => 'mail',
                'loan_application_id' => $contract->application_id,
                'client_name' => $user->first_name . ' ' . $user->last_name,
                'signed_date' => $this->formateDate(now()),
                'attachment' => array(
                    'path' => $file,
                    'name' => 'CashFasterAUCredit#' . $contract->contract_id . '.pdf',
                    'mime' => 'application/pdf',
                ),
            );

            $this->storeMsg($data);

            //send Inapp Notification
            $datas = array(
                'user_id' => $user->id,
                'subject' => 'Contract Signed',
                'content' => "Great news! Your loan application contract with CashFaster has been successfully signed. We're thrilled to move forward with your application. A copy of the signed contract has been sent to your email address.",
                'type' => 'inapp',
                'loan_application_id' => $contract->application_id,
            );

            $this->storeMsg($datas);


            return response()->json([
                "success" => true,
                "message" => 'Contract signed',
            ], 200);

        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'An error occurred.'], 500);
        }
    }
    public function contractAdminSigningViewed(Request $request)
    {
        try {
            $message = 'Already viewed';
            $input = $request->input();
            $ref_code = $input['data']['ref_code'];

            $contract = CashFasterContract::where('ref_code', $ref_code)->first();

            if (!$contract->view_date_cf) {
                $contract->ip_address_cf = $request->ip();
                $contract->view_date_cf = now();
                $contract->save();
                $message = 'Contract Viewed';
            }

            return response()->json([
                "success" => true,
                "message" => $message,
            ], 200);

        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'An error occurred.'], 500);
        }
    }

    function checkContractStatus($ref_code)
    {
        $contract = CashFasterContract::where('ref_code', $ref_code)->first();

        if ($contract->signed_date_cf && $contract->signed_date_customer) {
            $contract->status = 'completed';
            $contract->save();
        }

        return true;

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
            $signature_cf = '<div class="signature">' . $contract->signature_cf . '</div>';

        }

        $data = [
            'signature_cf' => $signature_cf,
            'signature_date_cf' => $contract->signed_date_cf,
            'signature_customer' => $signature_customer,
            'signature_date_customer' => $contract->signed_date_customer,
            'signature_day' => $this->dateFormater($contract->signed_date_customer, 'D'),
            'signature_month_year' => $this->dateFormater($contract->signed_date_customer, 'jS M,Y'),
            'contract' => $contract,
            'borrower' => $borrower
        ];


        $document = $this->replaceVariables($contract->document, $data);
        $data['document'] = $document;
        //  return view('admin.contract.pdf', $data);


        $pdf = PDF::loadView('admin.contract.pdf', $data);

        $filename = 'CashFasterAUCredit#' . $contract->contract_id . '.pdf';

        $contract->filename = $filename;
        $contract->save();

        $filepath = storage_path('app/public/contracts/' . $filename);
        // Save the PDF file to the storage path
        $pdf->save($filepath);

        // Stream the PDF as the response
        return $filepath;
    }

    public function preApproval(Request $request)
    {
        $app_id = $request->input('app_id');
        $customer_confirmation = $request->input('customer_confirmation');

        $loanapplication = LoanApplication::find($app_id);
        $user = $loanapplication->user;
        $loanapplication->customer_confirmation = $customer_confirmation;
        $loanapplication->save();

        $status = 'disagreed';

        if ($customer_confirmation == 1) {
            $status = 'agreed';
        }

        //Send email notification
        $admin = $this->listAdmin();

        foreach ($admin as $a) {
            $data = array(
                'user_id' => $a->id,
                'template' => 'approved-loan-amount-notification-admin',
                'type' => 'mail',
                'client_name' => $user->first_name . ' ' . $user->last_name,
                'loan_application_id' => $app_id,
                'approved_amount' => $loanapplication->approved_amount,
                'loan_amount' => $loanapplication->amount,
                'status' => $status
            );

            $this->storeMsg($data);
        }

        return response()->json([
            "success" => true,
            "message" => "Pre Approval request sent successfully",
        ], 200);

    }
}