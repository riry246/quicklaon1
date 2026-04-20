<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\MessageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    use MessageTrait;
    function index()
    {
        $data['heading'] = "Hi, Welcome Back";
        $data['sub_heading'] = "We're thrilled to see you again. Enter your credentials below to access your account.";
        $data['loancal'] = false;

        if (Auth::check()) {

            $data['user'] = Auth::user(); // Get the currently authenticated user
            $data['token'] = Auth::user()->createToken("API TOKEN")->plainTextToken;

            $dataArray['data'] = $data;

            return redirect('customer/dashboard');


        }


        return view('frontend.login', ['data' => $data]);
    }

    function verify(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'email' => 'required',
        ], [
            'password.required' => 'Password field is required',
            'email.required' => 'Email Address field is required'
        ]);

        $credentials = $request->only('email', 'password');

        $oldUser = $this->checkOldSystemUser($credentials['email']);

        if($oldUser){
            return back()->with('error', 'We have a new platform in Beta, we have detected your email is connected to an existing CashFaster account. <a href="https://members.cashfaster.com.au/login/" style="color:#fcc237"> Please click here to login</a>');
        }

        if (Auth::attempt($credentials)) {
            $groupName = 'Customer';
            $customer = User::with('groups')
                ->where('email', $credentials['email'])
                ->whereHas('groups', function ($query) use ($groupName) {
                    $query->where('name', $groupName);
                })
                ->first();

            if ($customer && $customer->status == 'active') {
                $token = $customer->createToken("API TOKEN")->plainTextToken;
                return redirect()->route('customer.dashboard');
            } 
            else {
                // Authentication failed due to inactive status
                return back()->with('error', 'Authentication failed: Not an active customer');
            }
        } else {
            // Authentication failed due to invalid credentials
            return back()->with('error', 'Authentication failed: Invalid username or password');
        }

    }

    public function checkOldSystemUser($email){
        $groupName = 'Customer';
        $customer = User::with('groups')
            ->where('email', $email)
            ->where('status','pending')
            ->whereHas('groups', function ($query) use ($groupName) {
                $query->where('name', $groupName);
            })
            ->first();

        if($customer){
            return true;
        }
        return false;
    }

    function resetPassword()
    {
        $data['heading'] = "Reset Password";
        $data['sub_heading'] = "Please enter your valid email address to recover your password.";
        $data['loancal'] = false;

        return view('frontend.reset_password', ['data' => $data]);
    }

    function sendResetPassword(Request $request)
    {
        $groupName = 'Customer';
        $email = $request->email;
        $password = Str::random(8);
        $data['password'] = bcrypt($password);
        $data['status'] = 'active';
        $customer = User::with('groups')
            ->where('email', $email)
            ->whereHas('groups', function ($query) use ($groupName) {
                $query->where('name', $groupName);
            })
            ->first();

        if ($customer && $customer->status == 'active') {
            $update = $customer->fill($data)->save();

            if ($update) {
                $first_name = $customer->first_name;
                $data = array(
                    'user_id' => $customer->id,
                    'template' => 'forget-password',
                    'type' => 'mail',
                    'password' => $password
                );
                
        
                $this->storeMsg($data);
            }
            return back()->with('success', 'Password reset mail sent successfully !');
        } else {
            return back()->with('error', 'This customer is either inactive or not in the system!');
        }
    }
}