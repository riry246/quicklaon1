<?php

namespace App\Http\Controllers\Admin\LoanApplication;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Traits\LoanTrait;
use App\Traits\MessageTrait;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BadDebtController extends Controller
{

    use MessageTrait, LoanTrait;
    public function updateBadDebtStatus(Request $request)
    {
        try {
            // Retrieve request data
            $appId = $request->input('id');
            $isBadDebt = $request->input('isBadDebt');

            if ($isBadDebt == "true") {
                $isBadDebt = 1;
            } else {
                $isBadDebt = 0;
            }


            $this->updateStatus($appId, $isBadDebt, 0);

            // Send Warning Email
            $loanApplication = LoanApplication::findOrFail($appId);
            $this->sendReminder($loanApplication);

            // Return a response indicating success
            return response()->json(['message' => 'Bad debt status updated successfully']);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateStatus($appId, $isBadDebt, $in_default)
    {
        try {
            // Find the loan application
            $loanApplication = LoanApplication::findOrFail($appId);

            // Update the bad debt status
            $loanApplication->is_bad_debt = $isBadDebt;
            $loanApplication->in_default = $in_default;
            $loanApplication->save();

            // Return a response indicating success
            return response()->json(['message' => 'Bad debt status updated successfully']);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function highRisk($appId, $status)
    {
        // Find the loan application
        $loanApplication = LoanApplication::findOrFail($appId);
        $loanApplication->high_risk_customer = $status;
        $loanApplication->save();

        return;

    }

    public function checkForBadDebt()
    {
        try {
            $currentDate = Carbon::now();
            $approvedCutoffDate = Carbon::now()->subDays(30);

            $loanApplications = LoanApplication::where('status', 'active')
                ->whereDate('approved_date', '<=', $approvedCutoffDate)
                ->get();

            foreach ($loanApplications as $application) {
                $latestPaidStatement = $application->getLatestPaidStatement;

                if ($latestPaidStatement) {
                    $this->highRisk($application->id, false);
                    $updatedDate = Carbon::parse($latestPaidStatement->updated_at);
                    $differenceInDays = $updatedDate->diffInDays($currentDate);

                    if ($differenceInDays >= 30 && $differenceInDays < 59) {
                        if ($application->in_default == 0) {
                            $this->updateStatus($application->id, 0, 1);
                        }
                    } elseif ($differenceInDays >= 60) {
                        if ($application->is_bad_debt == 0) {
                            $this->updateStatus($application->id, 1, 0);
                            $this->sendReminder($application);
                        }
                    }

                } else {
                    if ($application->is_bad_debt == 0) {
                        $this->updateStatus($application->id, 1, 0);
                        $this->sendReminder($application);
                    }
                    $this->highRisk($application->id, true);

                }

                //check for 200% Outstanding 
                $loan_amount = $application->approved_amount;
                $outstanding_amount = $this->getStatementTotalByStatus($application->id, ['Scheduled', 'Re-scheduled', 'Hold']);

                if ($outstanding_amount > 2 * $loan_amount) {
                    $application->excessive_outstanding_flag = true;
                    $application->save();
                    $this->updateStatement($application->id, ['Scheduled', 'Re-scheduled'], 'Hold');
                }

            }

            return redirect()->back()->with('success', 'Bad Debt accounts checked successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while checking for bad debt. Please try again later.');
        }
    }

    function sendReminder($application)
    {
        $user = $application->user;

        $datas = array(
            'user_id' => $user->id,
            'template' => 'warning-email-for-bad-debt',
            'type' => 'mail',
            'loan_application_id' => $application->id,
        );

        $this->storeMsg($datas);

        return true;

    }

}
