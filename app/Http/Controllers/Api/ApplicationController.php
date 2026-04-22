<?php

namespace App\Http\Controllers\Api;

use App\Models\IllionCustomerInfo;
use App\Traits\GeneralTrait;
use App\Traits\IllionTrait;
use App\Traits\MessageTrait;
use Illuminate\Http\Request;
use App\Models\LoanApplication;
use App\Http\Controllers\Controller;
use App\Models\OtpVerification;
use App\Models\Step;
use App\Models\UserAttr;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class ApplicationController extends Controller
{

    use GeneralTrait, MessageTrait, IllionTrait;

    public function sendSMS(Request $request)
    {
        try {
            $mobile = $request->input('mobile');

            $request->validate([
                'mobile' => 'required',
            ]);

            if (!$this->checkAustralianMobileNumber($mobile)) {
                //throw new \Exception('Please enter a valid Australian mobile number');
            }

            if ($this->getOTPCount($mobile)) {
                throw new \Exception('Limit Exceeded: You have reached the daily OTP request limit. Please try again tomorrow.');
            }

            $this->sendOTP($mobile);

            return response()->json(['success' => 'OTP Verification code sent'], 200);

        } catch (\Exception $e) {

            return response()->json(['message' => $e->getMessage()], 429);
        }
    }
    public function submitOTP(Request $request)
    {
        try {
            $request->validate([
                'otp' => 'required',
            ], [
                'otp.required' => 'Enter 4 digit OTP sent to your mobile',
            ]);

            $otp = $request->input('otp');
            $mobile = $request->input('mobile');

            // Verify the submitted OTP
            $validateOtp = $this->verifyOTP($mobile, $otp);

            if ($validateOtp) {
                $checkExistingUser = $this->getUserByNumber($mobile);

                $user = $checkExistingUser ? $this->checkUserByNumber($mobile) : $this->createUserByNumber($mobile);

                if ($user) {
                    $token = $user->createToken("API TOKEN")->plainTextToken;

                    // Update mobile verification status
                    $this->updateMobileVerify($mobile);

                    // Log in the user
                    Auth::login($user);

                    // Return a success response with token and user ID
                    return response()->json(['message' => 'OTP Verified successfully', 'token' => $token, 'user_id' => $user->id], 200);
                } else {
                    // Return an error response if something went wrong during user creation
                    throw new \Exception('Oops! Something went wrong. Please try again.');
                }
            }

            throw new \Exception('Verification Failed: The 4-digit code you entered is incorrect or expired. Please check your SMS and try again.');
        } catch (\Exception $e) {

            return response()->json(['message' => $e->getMessage()], 406);
        }
    }

    public function getApplicationInfo(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            $amount = $request->input('amount');
            $duration = $request->input('duration');

            $result = null;

            // Checking previous incomplete application
            $application = LoanApplication::where('user_id', $user_id)
                ->whereIn('status', ['pending', 'incomplete', 'active', 'processing'])
                ->latest('created_at')
                ->first();

            //illion status
            $referrerCode = $request->input('referrerCode');
            $status = $request->input('bankStatus');

            if ($referrerCode && $status == 'COMPLETE') {

                $illion = IllionCustomerInfo::firstOrNew([
                    'reference' => $referrerCode,
                ]);

                $illion->user_id = $user_id;
                $illion->loanapplication_id = $application->id;
                $illion->reference = $referrerCode;
                $illion->save();

            }

            $thirtyDaysAgo = Carbon::now()->subDays(30);

            $declinedapplication = LoanApplication::where('user_id', $user_id)
                ->whereIn('status', ['declined'])
                ->where('updated_at', '>', $thirtyDaysAgo) // Apply condition for applications less than 30 days old
                ->latest('updated_at')
                ->first();

            if ($declinedapplication) {
                $application = $declinedapplication;
            }

            $completedApplications = LoanApplication::where('user_id', $user_id)
                ->whereIn('status', ['completed'])
                ->count();



            $applicableLoan['amount'] = 2000;
            $applicableLoan['duration'] = 12;



            if (!$application) {
                // Create a new application
                $data['amount'] = $amount;
                $data['duration'] = $duration;
                $data['frequency'] = $request->input('frequency') ?: 'weekly';
                $data['status'] = 'incomplete';
                $data['user_id'] = $request->input('user_id');
                $data['step'] = 1;

                $application = $this->createApplication($data);
            } else {
                $userAttr = UserAttr::where('user_id', $user_id)->where('application_id', $application->id)->get();
                if ($userAttr) {
                    $userAttrArray = [];

                    foreach ($userAttr as $attr) {
                        $userAttrArray[$attr['attr']] = $attr['value'];
                    }
                    $result = $userAttrArray;
                }
            }

            $user = User::findOrFail($user_id);

            return response()->json(['success' => 'Application Information collected successfully', 'user' => $user, 'application' => $application, 'attributes' => $result, 'applicableLoan' => $applicableLoan], 200);

        } catch (\Exception $e) {
            // Return an error response with the exception message
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function storeApplicationData(Request $request)
    {
        try {
            ini_set('max_execution_time', 120);
            $message = null;
            $step = $request->input('step');
            $inputs = $request->input();
            $user_id = $inputs['user_id'];
            $exclude_array = ['step', '_token'];
            $formData = $inputs['formData'];

            $loanApplication = LoanApplication::where('user_id', $user_id)->latest('created_at')->firstOrFail();
            $app_id = $loanApplication->id;

            switch ($step) {
                case '1':
                    $this->updateUser(Arr::except($inputs['formData'], $exclude_array), $user_id);
                    $exclude_array = array_merge($exclude_array, ['first_name', 'middle_name', 'last_name', 'dob']);
                    break;
                case '7':
                    $this->addIllionBankInformation(Arr::except($inputs['formData'], $exclude_array), $user_id, $app_id);
                    $exclude_array = array_merge($exclude_array, ['institution', 'loginId', 'password', 'secret', 'accountNo', 'account_list', 'app_id']);
                    break;
            }
            // Specific operations based on step

            switch ($step) {
                case '300':
                    if ($formData['pay_cycle'] == 'weekly' && $formData['wages_after_tax'] < 500) {
                        $message = "Application is declined due to weekly income less than $500";
                        $this->DeclineLoanStatus($app_id, 'declined', $message);
                        $step = "9";
                    } else if ($formData['pay_cycle'] == 'fortnightly' && $formData['wages_after_tax'] < 1000) {
                        $message = "Application is declined due to fortnightly income less than $1000";
                        $this->DeclineLoanStatus($app_id, 'declined', $message);
                        $step = "9";
                    }

                    break;
                case '8':
                    $user = User::findOrFail($user_id);
                    if ($user->password !== null) {
                        $this->updateLoanStatus($app_id, 'pending');
                        $this->sendpushNotifications($user_id, $app_id);
                        $this->sendEmailNotification($user_id, $app_id);
                        $this->assignTocreditor($app_id);
                        $step = 9;
                    }
                    break;
                case '9':
                    $exists = User::where('email', $formData['email'])
                        ->where('status', 'active')->exists();
                    if ($exists) {
                        return response()->json(['message' => 'Email address already exists'], 406);
                    }
                    //delete old user
                    User::where('email', $formData['email'])
                        ->where('status', 'pending')
                        ->delete();

                    $this->updateUserEmail($formData, $user_id);
                    Auth::login(User::findOrFail($user_id));
                    $this->updateLoanStatus($app_id, 'pending');
                    $this->sendpushNotifications($user_id, $app_id);
                    $this->sendEmailNotification($user_id, $app_id);
                    $this->assignTocreditor($app_id);
                    $exclude_array = array_merge($exclude_array, ['email', 'password', 'repassword']);
                    break;
            }

            $formData = Arr::except($inputs['formData'], $exclude_array);

            // Insert User Attributes and Update step
            $this->insertUserattr($formData, $app_id, $user_id);
            $this->updateAppStep($app_id, ++$step);


            return response()->json(['success' => 'Application data stored successfully', 'next_step' => $step, 'message' => $message], 200);
        } catch (\Exception $e) {
            // Return an error response with the exception message
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    function addPaymentInfo(Request $request)
    {
        $inputs = $request->input('formData');
        $app_id = $inputs['app_id'];
        $user_id = $inputs['user_id'];

        $this->addBankInformation($inputs, $user_id, $app_id);

        return response()->json(['success' => 'Payment information added successfully'], 200);
    }
    function updateAmountDuration(Request $request)
    {
        $inputs = $request->input('app_id');

        $loan = LoanApplication::findorfail($inputs);
        $loan->amount = $request->input('amount');
        $loan->duration = $request->input('duration');
        $loan->frequency = $request->input('frequency');
        $loan->save();

        return response()->json(['success' => 'Loan detail updated successfully'], 200);

    }
    function sendpushNotifications($user_id, $app_id)
    {

        $url = route('loan.view', ['id' => $app_id]);
        //Send Database Notification
        $notification['icon'] = "ti-file-plus";
        $notification['color'] = "warning";
        $notification['heading'] = "Application Submitted";
        $notification['msg'] = "Application submitted for review";
        $notification['url'] = $url;

        $this->sendNotification($user_id, $notification);

        $notification['msg'] = "New Application ID #" . $app_id . " submitted for review";

        $admin = $this->getAdminUsers();

        foreach ($admin as $a) {
            $this->sendNotification($a->id, $notification);
        }

        return true;
    }
    function sendEmailNotification($user_id, $loan_id)
    {

        $data = array(
            'user_id' => $user_id,
            'template' => 'welcome-email',
            'type' => 'mail',
            'loan_application_id' => $loan_id,
        );

        $this->storeMsg($data);

        $admin = $this->listAdmin();

        foreach ($admin as $a) {
            $data = array(
                'user_id' => $a->id,
                'template' => 'new-loan-application-admin',
                'type' => 'mail',
                'loan_application_id' => $loan_id,
            );

            $this->storeMsg($data);
        }

        return;
    }
    function getIllionIframe(Request $request)
    {
        try {

            $loan = LoanApplication::find($request->input('app_id'));

            $data = $this->initialise($loan->user_id, $loan->id);

            return response()->json(['message' => 'Iframe url found', 'url' => $data['iframeUrl']], 200);

        } catch (\Exception $e) {

            return response()->json(['message' => $e->getMessage()], 406);
        }
    }

}