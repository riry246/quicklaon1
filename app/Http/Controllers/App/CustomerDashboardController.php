<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\CashFasterContract;
use App\Models\LoanTerm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        if (Auth::check()) {

            $data['user'] = Auth::user(); // Get the currently authenticated user
            $data['token'] = Auth::user()->createToken("API TOKEN")->plainTextToken;
            $data['section'] = 'dashboard';



            $dataArray['data'] = $data;

            return view('frontend.user.dashboard', $dataArray);
        }
        return redirect()->route('user.login');

    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect()->route('home');
    }

    public function autoLogin($id)
    {
        $user = User::findorfail($id);
        Auth::login($user);
        return response()->json(['success' => 'Login Success'], 200);
    }
    public function autoLoginCustomer($id)
    {

        $user = User::where('temp_login_token', $id)->first();
        if ($user) {

            Auth::login($user);

            if (Auth::check()) {

                $data['user'] = Auth::user(); // Get the currently authenticated user
                $data['token'] = Auth::user()->createToken("API TOKEN")->plainTextToken;

                $dataArray['data'] = $data;

                $user->temp_login_token = null;
                $user->save();

            }

            return redirect('https://app.cashfaster.com.au/customer/dashboard');
        } else {
            return redirect('https://app.cashfaster.com.au/');
        }

    }

    public function contract($slug)
    {
        $data['heading'] = "Contract Signing";
        $data['sub_heading'] = "Please review the contract carefully before signing. Your understanding is crucial. Once confident, proceed to sign, initiating our collaborative journey. Thank you for entrusting us with your financial goals.";
        $data['loancal'] = false;

        $data['contract'] = CashFasterContract::where('ref_code', $slug)->first();



        $dataArray['data'] = $data;
        return view('frontend.contract', $dataArray);
    }

    public function leadmarket($token)
    {
        $dataArray['token'] = $token;
        return view('frontend.leadmarket', $dataArray);
    }
    public function leadmarketBuy()
    {
        return view('frontend.leadmarketbuy');
    }
}