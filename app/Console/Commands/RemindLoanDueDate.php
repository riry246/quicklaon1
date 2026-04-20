<?php
// app/Console/Commands/RemindLoanDueDate.php

namespace App\Console\Commands;

use App\Models\LoanApplication;
use App\Models\LoanStatement;
use App\Traits\GeneralTrait;
use App\Traits\LoanTrait;
use App\Traits\MessageTrait;
use Illuminate\Console\Command;
use Carbon\Carbon;

class RemindLoanDueDate extends Command
{
    use MessageTrait, GeneralTrait, LoanTrait;

    protected $signature = 'remind:loan-due-date';
    protected $description = 'Send reminders for upcoming loan due dates';

    public function handle()
    {
        
        // Get loans with due dates within the next 2 days
        $nextDueDate = Carbon::now()->addDays(2)->toDateString();

        $loanStatement = LoanStatement::whereIn('payment_status', ['Scheduled', 'Re-scheduled'])
            ->whereDate('settlement_date', '=', $nextDueDate)
            ->get();

        foreach ($loanStatement as $statement) {
            // Extract user and loan details
            $loanApplication = LoanApplication::find($statement->loan_application_id);
            $user = $loanApplication->user;

            $this->sendReminder($user, $statement);

            $this->taskLog($user, $statement->loan_application_id, 'Reminder', 'Due date Reminder sent');
        }

        $this->info('Reminders sent for upcoming loan due dates.' . count($loanStatement));
    }

    function sendReminder($user, $statement)
    {
        $loanApplication = LoanApplication::find($statement->loan_application_id);

        $account = $user->monoovaAccount;

        $datas = array(
            array(
                'template' => 'due-date-reminder',
                'type' => 'mail',
                'due_date' => $this->formateDate($statement->settlement_date),
                'installment_amount' => number_format($statement->weekly_payment, 2, '.', ''),
                'loan_amount' => number_format($loanApplication->approved_amount, 2, '.', ''),
                'bankAccountName' => $account->bankAccountName,
                'bankAccountNumber' => $account->bankAccountNumber,
                'bsb' => $account->bsb,
                'payid' => $account->payid,
            ),
            array(
                'subject' => 'Next Repayment Reminder',
                'content' => 'Dear ' . $user->first_name . ', your next repayment of $ ' . $statement->weekly_payment . ' for your loan is due on ' . $this->formateDate($statement->settlement_date) . '. Thank you!',
                'type' => 'inapp',
            ),
            array(
                'type' => 'sms',
                'template' => 'due-date-reminder',
                'settlement_date' => $this->formateDate($statement->settlement_date),
                'weekly_payment' => number_format($statement->weekly_payment, 2, '.', ''),
            )
        );

        foreach ($datas as $d) {
            $d['user_id'] = $user->id;
            $d['loan_application_id'] = $statement->loan_application_id;
            $this->storeMsg($d);
        }

        //Send Database Notification
        $notification['icon'] = "ti-mail";
        $notification['color'] = "warning";
        $notification['heading'] = "Next Repayment Reminder";
        $notification['msg'] = 'Dear ' . $user->first_name . ', your next repayment of $ ' . $statement->weekly_payment . ' for your loan is due on ' . $this->formateDate($statement->settlement_date) . '. Thank you!';

        $this->sendNotification($user->id, $notification);
        $this->sendNotification('2', $notification);
        
    }

}
