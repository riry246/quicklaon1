<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoanApplication;
use App\Models\LoanStatement;
use App\Traits\BasiqTrait;
use App\Traits\GeneralTrait;
use App\Traits\LoanTrait;
use App\Traits\MessageTrait;
use App\Traits\MonoovaTrait;
use Carbon\Carbon;

class SendEmailController extends Controller
{
    use MonoovaTrait, LoanTrait, MessageTrait, GeneralTrait, BasiqTrait;
    public function index()
    {
        $today = Carbon::now()->toDateString();

        $loanStatement = LoanStatement::whereIn('payment_status', ['WaitingOnClearedFunds'])
            ->whereDate('settlement_date', '=', $today)
            ->get();

        $processedIds = [];

        foreach ($loanStatement as $statement) {
            // Check if loan_application_id has been processed before
            if (!in_array($statement->loan_application_id, $processedIds)) {
                $loan = LoanApplication::findOrFail($statement->loan_application_id);
                 $this->sendReminder($loan);
                echo 'email sent to ' . $loan->user->email . '(' . $loan->id . ')<br/>';
                // Add the processed ID to the array
                $processedIds[] = $statement->loan_application_id;
            } else {
                // If duplicate, skip processing
                echo 'Duplicate loan ID encountered: ' . $loan->id . '<br/>';
            }
        }

        echo 'done';
    }

    function sendReminder($application)
    {
        $user = $application->user;

        $datas = array(
            'user_id' => $user->id,
            'template' => 'apology-for-duplicate-debit',
            'type' => 'mail',
            'loan_application_id' => $application->id,
        );

        $this->storeMsg($datas);

        return true;

    }
}
