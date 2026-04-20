<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\DocumentType;
use App\Models\LoanApplication;
use App\Models\User;
use App\Models\UserIdVerification;
use App\Traits\GeneralTrait;
use App\Traits\MessageTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Mail\VerificationEmail;
use App\Mail\DocumentRequestEmail;
use App\Mail\IdRequestEmail;
use App\Mail\LoanProcessingEmail;
use App\Mail\PaymentVerificationRequestEmail;
use Illuminate\Support\Facades\Mail;

class RequestInformationController extends Controller
{

    use GeneralTrait, MessageTrait;

    public function requestDocument(Request $request, $id)
    {
        $data = $request->all();
        $documentData = $request->document_type;
        $document = null;

        //Validation
        $request->validate(
            [
                'document_type' => 'required',
            ],
            [
                'document_type.required' => 'Document Type field is required',
            ]
        );

        foreach ($documentData as $d) {

            $isexist = Documentation::where('document_type', Str::slug($d))
                ->where('loan_application_id', $id)
                ->where('status', 'sent')
                ->first();

            if (!$isexist) {
                $documentation = new Documentation();
                $documentation->loan_application_id = $id;
                $documentation->document_type = Str::slug($d);
                $documentation->status = 'sent';
                $documentation->requested_at = Carbon::now();
                $documentation->requested_by = Auth::user()->id;
                $documentation->save();
            }

            $document .= $d . ', ';
        }

        $loanApplication = LoanApplication::findorfail($id);
        $user = User::findorfail($loanApplication->user_id);
        $user->document_verified = 0;
        $user->save();

        //Send Email Notification
        $data = array(
            'user_id' => $user->id,
            'template' => 'document-required',
            'type' => 'mail',
            'document' => rtrim($document, ', '),
            'loan_application_id' => $loanApplication,
        );
        $this->storeMsg($data);

        //send Inapp Notification
        $datas = array(
            'user_id' => $user->id,
            'subject' => 'Document Request',
            'content' => "We need additional documents to process your loan application. Please upload the required documents in the 'Document Submission' section.",
            'type' => 'inapp',
            'loan_application_id' => $id,
        );

        $this->storeMsg($datas);

        //Send Database Notification
        $notification['icon'] = "ti-file-upload";
        $notification['color'] = "warning";
        $notification['heading'] = "Document Request";
        $notification['msg'] = "Please provide requested documents.";

        $this->sendNotification($loanApplication->user_id, $notification);

        $this->logActivity('Requested document for application: #' . $id, null);


        return redirect()->back()->with('success', 'Document request has been successfully sent.');

    }

    public function requestId(Request $request, $id)
    {
        $loanapplication = LoanApplication::findOrFail($id);
        $idverification = $loanapplication->user->rejectedidVerifcation;

        UserIdVerification::destroy($idverification);

        $user = User::findOrFail($loanapplication->user_id);
        $user->id_verified = false;
        $user->save();

        //Send Email Notification
        $data = array(
            'user_id' => $user->id,
            'template' => 'id-verification',
            'type' => 'mail',
            'loan_application_id' => $id,
        );
        $this->storeMsg($data);

        //send Inapp Notification
        $datas = array(
            'user_id' => $user->id,
            'subject' => 'ID Verification Request',
            'content' => "To enhance your account security, we require you to complete ID verification. Please follow the prompts in the 'Verify ID' section.",
            'type' => 'inapp',
            'loan_application_id' => $id,
        );

        $this->storeMsg($datas);

        //Send Database Notification
        $notification['icon'] = "ti-id";
        $notification['color'] = "warning";
        $notification['heading'] = "ID Verification Request";
        $notification['msg'] = "Please verify your ID.";

        $this->sendNotification($loanapplication->user_id, $notification);


        $this->logActivity('Requested ID Verification for application: #' . $id, null);


        return redirect()->back()->with('success', 'ID Verification link has been sent.');

    }

    public function requestBank(Request $request, $id)
    {
        $loanapplication = LoanApplication::findOrFail($id);

        $user = User::findOrFail($loanapplication->user_id);
        $user->bank_verified = false;
        $user->save();

        //Send Email Notification
        $data = array(
            'user_id' => $user->id,
            'template' => 'verify-bank-account',
            'type' => 'mail',
            'loan_application_id' => $id,
        );
        $this->storeMsg($data);

        //send Inapp Notification
        $datas = array(
            'user_id' => $user->id,
            'subject' => 'Bank Verification Request',
            'content' => "To proceed with your application, we require verification of your bank details. Please follow the prompts in the 'Verify Bank' section to complete the process.",
            'type' => 'inapp',
            'loan_application_id' => $id,
        );

        $this->storeMsg($datas);

        //Send Database Notification
        $notification['icon'] = "ti-building-bank";
        $notification['color'] = "warning";
        $notification['heading'] = "Bank Verification Request";
        $notification['msg'] = "Please verify your Bank Account Detail.";

        $this->sendNotification($loanapplication->user_id, $notification);





        $this->logActivity('Requested ID Verification for application: #' . $id, null);


        return redirect()->back()->with('success', 'Bank Verification request has been sent.');

    }

    public function emailVerification(Request $request, $id)
    {
        $data = $request->all();
        $loanapplication = LoanApplication::findOrFail($id);
        $user = User::findOrFail($loanapplication->user_id);
        $baseUrl = url('/');
        if ($user->email == $data['email']) {
            $request->validate(
                [
                    'email' => 'required|string|email|max:255',
                ],
                [
                    'email.required' => 'Email field is required',
                ]
            );
        } else {
            $request->validate(
                [
                    'email' => 'required|string|email|max:255|unique:users',
                ],
                [
                    'email.required' => 'Email field is required',
                    'email.unique' => 'Email address is already in use. Please choose a different email address.',
                ]
            );

            $user->email = $data['email'];
        }

        $user->email_verified_at = null;
        $user->email_verification_code = Str::random(64);
        $user->save();

        //Send Email Notification
        $datas = array(
            'user_id' => $user->id,
            'template' => 'email-verification',
            'type' => 'mail',
            'verificationLink' => $baseUrl . '/verify/email/' . $user->email_verification_code,
            'loan_application_id' => $id,
        );
        $this->storeMsg($datas);

        //send Inapp Notification
        $datas = array(
            'user_id' => $user->id,
            'subject' => 'Email Verification Request',
            'content' => "To activate your account and ensure security, we require you to verify your email address. Please check your mail and click the verify link to complete the verification",
            'type' => 'inapp',
            'loan_application_id' => $id,
        );

        $this->storeMsg($datas);

        //Send Database Notification
        $notification['icon'] = "ti-mail";
        $notification['color'] = "warning";
        $notification['heading'] = "Email Verification Request";
        $notification['msg'] = "Please verify your email address.";

        $this->sendNotification($loanapplication->user_id, $notification);


        $this->logActivity('An email verification request has been created for Application ID #' . $id, null);


        return redirect()->back()->with('success', 'An email verification link has been sent to ' . $data['email'] . ' email address.');
    }

    public function mobileVerification(Request $request, $id)
    {
        $data = $request->all();
        $loanapplication = LoanApplication::findOrFail($id);
        $user = User::findOrFail($loanapplication->user_id);
        if ($user->mobile == $data['mobile']) {
            $request->validate(
                [
                    'mobile' => 'required|string',
                ],
                [
                    'mobile.required' => 'Mobile field is required',
                ]
            );
        } else {
            $request->validate(
                [
                    'mobile' => 'required|integer|min:9|unique:users',
                ],
                [
                    'mobile.required' => 'Mobile field is required',
                    'mobile.unique' => 'Mobile is already in use. Please choose a different mobile number.',
                ]
            );

            $user->mobile = $data['mobile'];
            $isvalidnumber = $this->checkAustralianMobileNumber($data['mobile']);




            if (!$isvalidnumber) {

                //Send Database Notification
                $notification['icon'] = "ti-device-mobile";
                $notification['color'] = "warning";
                $notification['heading'] = "Mobile Verification Request";
                $notification['msg'] = "Please verify your mobile number.";

                $this->sendNotification($loanapplication->user_id, $notification);

                return redirect()->back()->with('error', 'Please enter valid Australian mobile number');

            }
        }

        $user->mobile_verified_at = null;
        $user->save();

        //send SMS
        $this->sendOTP($user->mobile);


        $this->logActivity('An mobile verification request has been created for Application ID #' . $id, null);


        return redirect()->back()->with('success', 'An mobile verification code has been sent to ' . $data['mobile'] . '.');
    }

    public function sendReminder(Request $request, $id)
    {
        $data = $request->all();
        $loanapplication = LoanApplication::findOrFail($id);
        $user = User::findOrFail($loanapplication->user_id);

        //if email address
        if ($user->email) {
            $datas = array(
                'user_id' => $user->id,
                'template' => 'application-completion-reminder',
                'type' => 'mail',
                'loan_application_id' => $id,
            );

            $this->storeMsg($datas);
        }

        $datas = array(
            'user_id' => $user->id,
            'subject' => 'Complete application',
            'content' => 'Please finalize your application for uninterrupted service. Thank you!',
            'type' => 'inapp',
            'loan_application_id' => $id,
        );

        $this->storeMsg($datas);

        $datas = array(
            'user_id' => $user->id,
            'type' => 'sms',
            'template' => 'request-to-complete-application',
            'loan_application_id' => $id,
        );

        $this->storeMsg($datas);

        //Send Database Notification
        $notification['icon'] = "ti-mail";
        $notification['color'] = "warning";
        $notification['heading'] = "Complete application";
        $notification['msg'] = "Please finalize your application for uninterrupted service. Thank you!";

        $this->sendNotification($loanapplication->user_id, $notification);

        return redirect()->back()->with('success', "Reminder to complete application has been sent.");
    }

}