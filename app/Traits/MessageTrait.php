<?php

namespace App\Traits;

use App\Models\FcmToken;
use App\Models\Message;
use App\Models\OtpVerification;
use App\Models\SMSTemplate;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Exception\RequestException;
use Twilio\Rest\Client as TwilioClient;
use App\Models\MailTemplate;
use Auth;


trait MessageTrait
{
    public function userInfo($id, $param)
    {
        $user = User::find($id);
        return $user->$param;
    }

    public function storeMsg($data)
    {
        try {
            $status = 'sent';
            $user_id = $data['user_id'] ?? null;
            $subject = $data['subject'] ?? null;
            $content = $data['content'] ?? null;
            $message = new Message();

            switch ($data['type']) {

                case 'inapp':
                    $inapp = $this->inappNotification($data);
                    $status = $inapp && $inapp['success'] == 1 ? 'sent' : 'failed';
                    break;

                case 'sms':
                    $data['mobile'] = $user_id ? $this->userInfo($user_id, 'mobile') : $data['mobile'];
                    $data['first_name'] = $user_id ? $this->userInfo($user_id, 'first_name') : null;

                    $sms = $this->sendSmsMsg($data);

                    if ($sms) {
                        $subject = $sms['subject'];
                        $content = $sms['content'];
                        $status = $sms['status'];
                    }
                    break;

                case 'mail':
                    $mail = $this->sendEmail($data);

                    if ($mail) {
                        $subject = $mail['subject'];
                        $content = $mail['content'];
                    }

                    break;

                default:
                    throw new \InvalidArgumentException("Unsupported message type: {$data['type']}");
            }

            $message->user_id = $user_id;
            $message->type = $data['type'];
            $message->subject = ucfirst($subject);
            $message->content = $content;
            $message->loan_application_id = $data['loan_application_id'] ?? null;
            $message->status = $status;
            $message->created_by = Auth::id() ?? 0;
            $message->save();

            return true;

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to process message', 'message' => $e->getMessage()], 500);
        }
    }
    public function sendOTP($mobile)
    {
        try {

            $user = User::where('mobile', $mobile)->first();

            //$data['code'] = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $data['code'] = '1234';
            $data['user_id'] = $user ? $user->id : null;
            $data['mobile'] = $mobile;
            $data['type'] = 'sms';
            $data['template'] = 'otp-verification';

            $this->storeMsg($data);

            $otp = new OtpVerification();
            $otp->mobile_number = $mobile;
            $otp->otp = $data['code'];
            $otp->valid_till = Carbon::now()->addMinutes(5);
            $otp->save();

            return true;

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send OTP', 'message' => $e->getMessage()], 500);
        }

    }
    public function sendAuthCodetoAdmin()
    {
        try {
            $user = auth()->user();
            $data['code'] = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $data['user_id'] = $user ? $user->id : null;
            $data['type'] = 'sms';
            $data['template'] = 'payment-authorisation';

            $this->storeMsg($data);

            $otp = new OtpVerification();
            $otp->mobile_number = $user->mobile;
            $otp->otp = $data['code'];
            $otp->valid_till = Carbon::now()->addMinutes(5);
            $otp->save();

            return true;

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send authentication code', 'message' => $e->getMessage()], 500);
        }
    }
    public function sendAuthCodetoAdminNumberWise($mobile)
    {
        try {
            $user = User::where('mobile', $mobile)->first();
            if ($user) {
                $data['code'] = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                $data['user_id'] = $user ? $user->id : null;
                $data['type'] = 'sms';
                $data['template'] = 'payment-authorisation';

                $this->storeMsg($data);

                $otp = new OtpVerification();
                $otp->mobile_number = $user->mobile;
                $otp->otp = $data['code'];
                $otp->valid_till = Carbon::now()->addMinutes(5);
                $otp->save();

                return true;
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send authentication code', 'message' => $e->getMessage()], 500);
        }
    }
    public function inappNotification($data)
    {
        try {
            $token = FcmToken::where('user_id', $data['user_id'])->first();
            $subject = $data['subject'] ?? null;
            $content = $data['content'] ?? null;

            if (!$token) {
                return false;
            }

            $device_token = $token->token;

            if (isset($data['template'])) {
                $user = User::find($data['user_id']);
                $data['first_name'] = $user->first_name;

                $template = SMSTemplate::where('slug', $data['template'])->first();
                $content = $this->replaceVariables($template->body, $data);
                $subject = $template->subject;
            }

            $payload = [
                'notification' => [
                    'title' => ucfirst($subject),
                    'body' => strip_tags($content),
                ],
                'to' => $device_token,
            ];

            $headers = [
                'Authorization' => 'Bearer ' . env('FCM_SERVER_KEY'),
                'Content-Type' => 'application/json'
            ];

            $client = new Client();

            $response = $client->post('https://fcm.googleapis.com/fcm/send', [
                'headers' => $headers,
                'json' => $payload,
            ]);

            $statusCode = $response->getStatusCode();
            $responseData = json_decode($response->getBody(), true);

            return $responseData;

        } catch (RequestException $e) {

            return $e->getMessage();

        } catch (\Exception $e) {

            return $e->getMessage();

        }

    }
    public function sendSmsMsg($data)
    {

        try {

            $template = $data['template'];
            $sid = env('TWILIO_ACCOUNT_SID');
            $token = env('TWILIO_AUTH_TOKEN');
            $twilio = new TwilioClient($sid, $token);

            $template = SMSTemplate::where('slug', $template)->first();
            
            $to = '+61' . ltrim($data['mobile'], '0');
            $message = $this->replaceVariables($template->body, $data);
           
            $messageInstance = $twilio->messages->create(
                $to,
                [
                    'from' => env('TWILIO_PHONE_NUMBER'),
                    'body' =>strip_tags($message),
                ]
            );

            return array('subject' => $template->subject, 'content' => strip_tags($message), 'status' => $messageInstance->status);

        } catch (\Exception $e) {

            return array('subject' => $template->subject, 'content' => strip_tags($message), 'status' => 'error');
        }

    }
    public function sendEmail($data)
    {
        try {
            $template = $data['template'];
            $user = User::find($data['user_id']);
            $email = $user->email;

            if ($email) {
                $data['first_name'] = $user->first_name;
                $email = $this->manageEmail($template, $email, $data);

                return $email;

            } else {
                return false;
            }

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send email', 'message' => $e->getMessage()], 500);
        }

    }
    public function manageEmail($templateSlug, $to, $data = [])
    {
        try {
            $template = MailTemplate::where('slug', $templateSlug)->first();
            $subject = $this->replaceVariables($template->subject, $data);
            $body = $this->replaceVariables($template->body, $data);

            $viewData = [
                'subject' => $subject,
                'body' => $body,
            ];

            Mail::send('admin.email.template', $viewData, function ($message) use ($to, $subject, $data) {
                $message->to($to)
                    ->subject($subject);

                $message->cc('notifications@cashfaster.com.au');

                if (isset ($data['attachment'])) {
                    $attachment = $data['attachment'];
                    $message->attach($attachment['path'], [
                        'as' => $attachment['name'],
                        'mime' => $attachment['mime'],
                    ]);
                }
            });

            return array('success' => true, 'subject' => $subject, 'content' => $body);
        } catch (\Exception $e) {

            return array('success' => false, 'error' => $e->getMessage());
        }
    }

    private function replaceVariables($content, $data)
    {
        foreach ($data as $key => $value) {
            if ($key !== 'attachment') {
                $content = str_replace("{{ $key }}", $value, $content);
            }
        }

        return $content;
    }
}

