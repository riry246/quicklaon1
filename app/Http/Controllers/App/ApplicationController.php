<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\OtpVerification;
use App\Models\Step;

use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class ApplicationController extends Controller
{
    use GeneralTrait;

    public function index(Request $request)
    {
        $data['heading'] = "Let get started with your loan application";
        $data['sub_heading'] = "Begin your loan application with CashFaster to receive in outcome instantly!";
        $data['loancal'] = false;

        $data['amount'] = $request->get('amount');
        $data['duration'] = $request->get('duration');
        $data['frequency'] = $request->get('frequency');
        $data['step'] = 'mobile-verify';

        $dataArray['data'] = $data;


        return view('frontend.application', $dataArray);
    }
    function applicationForm($step)
    {
        $data['heading'] = "Let get started with your loan application";
        $data['sub_heading'] = "Begin your loan application with CashFaster to receive in outcome instantly!";
        $data['loancal'] = true;

        if ($step):
            $data['step'] = $step;
            $step_id = Step::where('slug', $step)->first();
            if ($step_id):
                $data['step_no'] = $step_id->id;
            endif;
        else:
            $data['step'] = 'login';
        endif;
        $dataArray['data'] = $data;
        return view('frontend.application', $dataArray);
    }

    public function verifyemail($token)
    {
        $user = User::where('email_verification_code', $token)->first();

        if ($user) {
            $user->email_verification_code = null;
            $user->email_verified_at = now();
            $user->save();

            $this->addScore($user->id, 'email-verification');

            return redirect()->route('customer.dashboard')->with('success', 'Email verified succesfully!');
        } else {
            return redirect()->route('user.login')->with('error', 'Email verification failed');
        }


    }
}