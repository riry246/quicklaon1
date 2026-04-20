<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Traits\MessageTrait;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use MessageTrait;
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validateUser = Validator::make(
            $credentials,
            [
                'email' => 'required|email',
                'password' => 'required'
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => false,
                'message' => 'Email & Password does not match with our record.',
            ], 401);
        }

        $groupName = 'Customer';
        $customer = User::with('groups')
            ->where('email', $credentials['email'])
            ->whereHas('groups', function ($query) use ($groupName) {
                $query->where('name', $groupName);
            })
            ->first();

        if ($customer && $customer->status == 'active') {
            // Authentication successful
            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'user_id' => $customer->id,
                'display_name' => $customer->first_name . ($customer->middle_name ? ' ' . $customer->middle_name : '') . ' ' . $customer->last_name,
                'token' => $customer->createToken("API TOKEN")->plainTextToken
            ], 200);
        } else {
            // Authentication failed
            $error = "Unauthorized";
            return response()->json(['success' => $error], 406);
        }
    }

    public function resetPassword()
    {
        $data['heading'] = "";
        $data['sub_heading'] = "Please enter your valid email address to recover your password.";
        $data['loancal'] = false;
       
        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    public function sendResetPassword(Request $request)
    {
        $groupName = 'Customer';
        $email = $request->email;
        $password = Str::random(8);
        $data['password'] = bcrypt($password);
        $customer = User::with('groups')
            ->where('email', $email)
            ->whereHas('groups', function ($query) use ($groupName) {
                $query->where('name', $groupName);
            })
            ->first();

        if ($customer && $customer->status == 'active') {
            $update = $customer->fill($data)->save();

            if ($update) {
                $data = array(
                    'user_id' => $customer->id,
                    'template' => 'forget-password',
                    'type' => 'mail',
                    'password' => $password
                );
                
                $this->storeMsg($data);
            }
            return response()->json(['success' => 'Password reset mail sent successfully !'], 200);
        } else {
            return response()->json(['error' => 'This customer is either inactive or not in the system !'], 406);
        }
    }
}
