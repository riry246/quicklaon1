<?php

namespace App\Traits;

use App\Models\ActivityScore;
use App\Models\BankAccount;
use App\Models\CashfasterScore;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\IllionBankAccount;
use App\Models\Referral;
use App\Models\TaskLog;
use App\Models\UserActivity;
use Carbon\Carbon;
use App\Models\Bank;
use App\Models\User;
use App\Models\UserAttr;
use App\Models\LoanApplication;
use App\Models\OtpVerification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Notifications\SendNotification;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

trait GeneralTrait
{
    use MessageTrait;
    public function pageTitle($title, $module_name)
    {
        $arr = array(
            'title' => $title,
            'module_name' => $module_name
        );
        return $arr;
    }

    public function breadcrumb($module_name, $function_name, $url, $id)
    {
        $arr = array(
            'module_name' => $module_name,
            'function' => $function_name,
            'url' => $url,
            'id' => $id
        );
        return $arr;
    }

    public function checkEnabledStatus($data)
    {
        if (!empty($data['status'])) {
            return 'active';
        } else {
            return 'inactive';
        }
    }


    public function checkAustralianMobileNumber($mobile)
    {
        // Regular expression pattern for Australian mobile numbers
        $pattern = "/^04\d{8}$/";

        if (!preg_match($pattern, $mobile)) {
            return false;
        }

        return true;

    }


    public function getOTPCount($mobile)
    {
        $otpCount = OtpVerification::where('mobile_number', $mobile)->whereDate('valid_till', today())->count();

        if ($otpCount >= 5) {
            return true;
        }
        return false;
    }

    public function verifyOTP($mobile, $otp)
    {
        $verification = OtpVerification::where('mobile_number', $mobile)
            ->where('otp', $otp)
            ->orderBy('id', 'desc')
            ->first();

        if ($verification) {
            $validTill = Carbon::parse($verification->valid_till);
            // Check if the valid_till timestamp is less than 5 minutes from now
            if ($validTill->isAfter(now()->subMinutes(5))) {
                return true;
            }
        }

        return false;
    }
    public function VerifyAuthCodetoAdmin($otp)
    {
        $user = auth()->user();
        $verification = OtpVerification::where('mobile_number', $user->mobile)
            ->where('otp', $otp)
            ->orderBy('id', 'desc')
            ->first();

        if ($verification) {
            $validTill = Carbon::parse($verification->valid_till);

            // Check if the valid_till timestamp is less than 5 minutes from now
            if ($validTill->isAfter(now()->subMinutes(5))) {
                return true;
            }
        }

        return false;
    }
    public function VerifyAuthCodetoAdminbyNumber($number, $otp)
    {
        $verification = OtpVerification::where('mobile_number', $number)
            ->where('otp', $otp)
            ->orderBy('id', 'desc')
            ->first();

        if ($verification) {
            $validTill = Carbon::parse($verification->valid_till);

            // Check if the valid_till timestamp is less than 5 minutes from now
            if ($validTill->isAfter(now()->subMinutes(5))) {
                return true;
            }
        }

        return false;
    }
    public function getUserByNumber($mobile)
    {
        $user = User::where('mobile', $mobile)->first();

        if ($user) {
            return true;
        }
        return false;
    }

    public function createUserByNumber($mobile)
    {
        $user = new User;
        $user->mobile = $mobile;
        $user->save();

        $group = Group::where('slug', 'customer')->first();
        $user->groups()->attach($group->id);

        if ($user) {
            $this->createReferralCode($user);
            return $user;
        }
        return false;
    }
    public function createReferralCode($user)
    {
        if ($user->referral) {
            return true;
        }

        $code = $this->generateReferralCode();

        $referral = new Referral;
        $referral->user_id = $user->id;
        $referral->code = $code;
        $referral->save();
        return true;
    }

    public function generateReferralCode()
    {
        $code = Str::random(8);

        while (Referral::where('code', $code)->exists()) {
            $code = Str::random(8);
        }

        return $code;
    }

    public function checkUserByNumber($mobile)
    {
        $user = User::where('mobile', $mobile)->first();
        if ($user) {
            return $user;
        }
        return false;
    }

    public function updateMobileVerify($mobile)
    {
        $user = User::where('mobile', $mobile)->first();
        $user = User::findOrFail($user->id);
        $user->mobile_verified_at = now();
        $user->save();

        return true;
    }

    public function updateUser($data, $id)
    {
        $risk_flag = 0;

        $duplicateUser = User::where('first_name', ucfirst($data['first_name']))
            ->where('last_name', ucfirst($data['last_name']))
            ->where('dob', $data['dob'])
            ->first();

        if ($duplicateUser) {
            if ($duplicateUser->id !== $id) {
                $risk_flag = 1;
                $duplicateUser->risk_flag = $risk_flag;
                $duplicateUser->save();
            }
        }


        $user = User::findOrFail($id);
        $user->first_name = ucfirst($data['first_name']);
        $user->middle_name = ucfirst($data['middle_name']);
        $user->last_name = ucfirst($data['last_name']);
        $user->dob = $data['dob'];
        $user->risk_flag = $risk_flag;
        $user->status = 'active';
        $user->save();

        return true;
    }
    public function updateUserEmail($data, $id)
    {

        $user = User::findOrFail($id);
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->status = 'active';
        $user->save();

        return true;
    }

    public function addBankInformation($data, $id, $app_id)
    {
        $bank = new BankAccount;
        $bank->user_id = $id;
        $bank->application_id = $app_id;
        $bank->basiq_code = $data['institution'];
        // $bank->username = $data['loginId'];
        // $bank->password = $data['password'];
        $bank->secret = $data['secret'];
        $bank->primary_account = $data['accountNo'];
        $bank->statements = json_encode($data['account_list']);
        $bank->verified_at = now();
        $bank->status = 'active';
        $bank->save();

        //Update user tabel
        $user = User::findorfail($id);
        $user->bank_verified = true;
        $user->save();
        return true;
    }
    public function addIllionBankInformation($data, $id, $app_id)
    {
        $account = IllionBankAccount::where([
            ['account_number', $data['accountNo']],
            ['loanapplication_id', $app_id],
            ['status', 1],
        ])->first();

        if ($account) {
            $account->primary_account = 1;
            $account->save();
        } else {
            return false;
        }

        return true;
    }
    public function updateLoanStatus($app_id, $status)
    {
        $loan = LoanApplication::findOrFail($app_id);
        $loan->status = $status;
        $loan->save();

        return true;
    }
    public function DeclineLoanStatus($app_id, $status, $message)
    {
        $loan = LoanApplication::findOrFail($app_id);
        $loan->status = $status;
        $loan->rejection_reason = $message;
        $loan->save();

        return true;
    }

    public function insertUserattr($arr, $app_id, $user_id)
    {
        //dd($user_id);
        foreach ($arr as $k => $v) {

            $attr = UserAttr::where('user_id', $user_id)
                ->where('application_id', $app_id)
                ->where('attr', Str::snake($k))
                ->first();

            if (!$attr) {
                $attr = new UserAttr;
            }

            $attr->user_id = $user_id;
            $attr->application_id = $app_id;
            $attr->attr = Str::snake($k);
            $attr->value = $v;
            if ($v) {
                $attr->save();
            }

        }

        return true;
    }

    public function createApplication($data)
    {
        $application = new LoanApplication;
        $application->amount = $data['amount'];
        $application->duration = $data['duration'];
        $application->frequency = $data['frequency'];
        $application->user_id = $data['user_id'];
        $application->application_date = now();
        $application->step = $data['step'];
        $application->status = $data['status'];
        $application->save();

        return $application;
    }

    public function updateAppStep($app_id, $step)
    {

        $application = LoanApplication::findOrFail($app_id);
        $application->step = $step;
        $application->save();

        return true;
    }

    function assignTocreditor($app_id)
    {
        // Array of user IDs
        $userIds = [3471, 6003];

        // Get a random user ID from the array
        $assigned_to = $userIds[array_rand($userIds)];

        // Find the loan application by its ID
        $loanApplication = LoanApplication::find($app_id);

        // Update the 'assign_to' attribute of the loan application
        $loanApplication->assign_to = $assigned_to;

        // Save the changes to the loan application
        $loanApplication->save();

        return true;
    }
    public function createBank($data)
    {
        $bank = Bank::create($data);
        return $bank;
    }

    private function logActivity($activity, $description)
    {
        $user = auth()->user();
        UserActivity::create([
            'user_id' => $user->id,
            'activity' => $activity,
            'description' => $description,
        ]);
    }

    private function sendNotification($notifiable_id, $notification)
    {

        User::find($notifiable_id)->notify(new SendNotification(json_encode($notification)));

        return;
    }

    public function getAdminUsers()
    {
        $user = User::whereDoesntHave('groups', function ($query) {
            $query->where('slug', 'customer');
        })->with('groups')->get();

        return $user;
    }

    public function addScore($user_id, $activity)
    {

        $score = ActivityScore::where('slug', $activity)->first();
        $loanApplication = LoanApplication::where('user_id', $user_id)->latest('created_at')->first();

        if ($score) {
            $cashfasterScore = new CashfasterScore;
            $cashfasterScore->user_id = $user_id;
            $cashfasterScore->application_id = $loanApplication->id;
            $cashfasterScore->score = $score->score;
            $cashfasterScore->activities = $score->id;
            $cashfasterScore->save();
        }

        return true;
    }

    function sendpushNotification($title, $body, $token)
    {
        $payload = [
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'to' => $token,

        ];

        $headers = [
            'Authorization' => 'Bearer ' . env('FCM_SERVER_KEY'),
            'Content-Type' => 'application/json'
        ];

        try {
            $client = new Client();
            $response = $client->post('https://fcm.googleapis.com/fcm/send', [
                'headers' => $headers,
                'json' => $payload,
            ]);

            $statusCode = $response->getStatusCode();
            $responseData = json_decode($response->getBody(), true);

            return $responseData;

        } catch (RequestException $e) {
            return $e->message;
        }

    }
    public function getUserName($id)
    {
        $user = User::find($id);

        if ($user) {
            $fullName = trim($user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name);

            // Replace consecutive spaces with a single space
            $fullName = preg_replace('/\s+/', ' ', $fullName);

            return $fullName;
        }

        return null;

    }
    public function formateDate($date)
    {
        if ($date) {
            $timestamp = strtotime($date);
            return date('d-m-Y', $timestamp);
        }
        return;
    }

    public function dateFormater($date, $format)
    {
        if ($date) {
            $timestamp = strtotime($date);
            return date($format, $timestamp);
        }
        return;
    }

    function refCode()
    {
        $uuid = Str::uuid();
        return str_replace('-', '', $uuid);
    }

    function taskLog($user, $loanApplicationId, $type, $action)
    {
        TaskLog::create([
            'user_id' => $user->id,
            'loan_application_id' => $loanApplicationId,
            'type' => $type,
            'action' => $action,
            'created_at' => now(),
        ]);

        return;
    }

    function listAdmin()
    {
        $list = User::whereNotNull('email')
            ->whereDoesntHave('groups', function ($query) {
                $query->where('slug', 'customer');
            })
            ->with('groups')
            ->orderBy('id', 'desc')
            ->get();

        return $list;
    }

    public function createUser($data)
    {
        $new = false;

        if ($data->email) {
            $user = User::where('email', $data->email)
                ->first();
        } elseif ($data->mobile) {
            $user = User::where('mobile', $data->mobile)
                ->first();
        }


        if (!$user) {
            $new = true;
            $password = Str::random(8);
            $user = new User;
            $user->password = $password;
        }
        // Create a new user

        $user->email = $data->email ?? null;
        $user->mobile = $data->mobile ?? null;
        $user->first_name = $data->first_name;
        $user->middle_name = $data->middle_name ?? null;
        $user->last_name = $data->last_name;
        $user->status = 'active';
        $user->dob = $data->date_of_birth;
        $user->save();



        if ($new) {
            $group = Group::where('slug', 'customer')->first();
            $user->groups()->attach($group->id);

            $this->createReferralCode($user);
            $this->notifyUser($user, 'lead-market-application-new', $password);
        } else {
            $this->notifyUser($user, 'lead-market-application', null);
        }

        return $user;
    }

    function notifyUser($user, $template, $password)
    {
        if ($user->email) {
            $data = array(
                'user_id' => $user->id,
                'template' => $template,
                'type' => 'mail',
                'password' => $password,
            );

            $this->storeMsg($data);
        }

        if ($user->mobile) {
            $data = array(
                'user_id' => $user->id,
                'template' => $template,
                'type' => 'sms',
                'password' => $password,
            );

            $this->storeMsg($data);
        }

        return;


    }

    function getAccountListofActiveBank($data)
    {

        if (isset($data->statements)) {
            $accounts = json_decode($data->statements);

            $account_list = [];

            foreach ($accounts as $account) {
                $account_list[] = array(
                    'account_no' => $account->accountNo,
                    'name' => $account->name
                );

            }

            return $account_list;
        }
        return [];

    }

    public function getAdminGroup()
    {
        $user = Auth::user();
        $usergroup = null;

        // Assuming the 'groups' relationship is defined on the User model
        $groups = $user->groups;

        // Assuming each user can be associated with multiple groups
        foreach ($groups as $group) {
            // echo "Group Slug: " . $group->slug . PHP_EOL;
            $usergroup = $group->slug;
        }

        return $usergroup;

    }

    public function getUserAttr($id,$attr)
    {
        $userattr = UserAttr::where('application_id', $id)->where('attr', $attr)->first();

        return $userattr->value ?? null;
    }
}