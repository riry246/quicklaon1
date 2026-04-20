<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\FcmToken;
use App\Models\LoanApplication;
use App\Models\LoanStatement;
use App\Models\User;
use App\Traits\GeneralTrait;
use App\Traits\LoanTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;

class CustomerDashboardController extends Controller
{
    use GeneralTrait, LoanTrait;
    public function getApplication(Request $request)
    {

        $currentDate = Carbon::now();

        $user = User::findOrFail($request->input('user_id'));
        $referral_code = $user->referral;
        $loanApplication = LoanApplication::where('user_id', $request->input('user_id'))->latest('created_at')->first();
        $document = Documentation::where('loan_application_id', $loanApplication->id)->where('status', 'sent')->get();
        $loanstatement = $loanApplication->statement;
        $rescheduledstatement = $loanApplication->rescheduledstatement;
        $contract = $loanApplication->latestcontract;
        $upcoming = LoanStatement::where('loan_application_id', $loanApplication->id)->where('payment_date', '>', $currentDate)->first();
        $score = $user->cashfasterScores->sum('score');
        $payment_method = $user->monoovaAccount;
        $leadmarket = $loanApplication->leadMarket;

        $totalPayable = $this->totalPayableAmount($loanApplication->id);
        $completedStatement = $this->completedStatement($loanApplication->id);

        $completedApplications = LoanApplication::where('user_id', $user->id)
            ->whereIn('status', ['completed'])
            ->count();

        $applicableLoan = ($completedApplications > 1)
            ? ['amount' => 1500, 'duration' => 8]
            : ['amount' => ($completedApplications == 1) ? 1000 : 500, 'duration' => 8];


        if ($loanApplication) {
            return response()->json([
                'success' => 'Application found',
                'loanApplication' => $loanApplication,
                'user' => $user,
                'referral_code' => $referral_code,
                'document' => $document,
                'loanstatement' => $loanstatement,
                'rescheduledstatement' => $rescheduledstatement,
                'upcoming' => $upcoming,
                'score' => $score,
                'totalPayable' => (double)$totalPayable,
                'completedStatement' => $completedStatement,
                'applicableLoan' => $applicableLoan,
                'contract' => $contract,
                'payment_method' => $payment_method,
                'leadmarket' => $leadmarket
            ], 200);
        }

        return response()->json(['message' => 'Oops something went worng'], 406);
    }

    public function updateProfile(Request $request)
    {
        try {
            $user_id = $request->input('user_id');

            $user = User::findOrFail($user_id);
            $user->first_name = ucfirst($request->input('first_name'));
            $user->middle_name = ucfirst($request->input('middle_name'));
            $user->last_name = ucfirst($request->input('last_name'));
            $user->dob = $request->input('dob');

            if ($request->input('oldPassword') !== null) {
                // Check if the provided old password matches the current password
                if (Hash::check($request->input('oldPassword'), $user->password)) {
                    // If the old password is correct, update the password
                    $user->password = Hash::make($request->input('newPassword'));
                } else {
                    // If the old password is incorrect, you might want to handle this scenario
                    return response()->json(['message' => 'Incorrect old password'], 422);
                }
            }
            $user->save();

            return response()->json([
                "success" => true,
                "message" => "User profile updated successfully",
            ], 200);

        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'An error occurred.'], 500);
        }
    }

    public function logout($id)
    {
        try {
            $fcm = FcmToken::where('user_id', $id)->first();

            if ($fcm) {
                $fcm->user_id = null;
                $fcm->save();

                return response()->json([
                    "success" => true,
                    "message" => "User logout successfully",
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "User not found for logout",
                ], 404);
            }
        } catch (\Exception $e) {
            // Log the exception or handle it appropriately
            return response()->json([
                "success" => false,
                "message" => "An error occurred during logout",
                "error" => $e->getMessage(),
            ], 500);
        }
    }

}