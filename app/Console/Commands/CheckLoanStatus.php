<?php

namespace App\Console\Commands;

use App\Models\LoanApplication;
use App\Traits\GeneralTrait;
use App\Traits\MessageTrait;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckLoanStatus extends Command
{
    use MessageTrait, GeneralTrait;
    protected $signature = 'check:loan-status';

    protected $description = 'Check for incomplete loan status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the current date and subtract 1 day
        $oneDayAgo = Carbon::now()->subDay()->toDateString();

        // Get the date 2 days ago
        $twoDaysAgo = Carbon::now()->subDays(2)->toDateString();

        // Retrieve loan applications that are incomplete and created more than 1 day ago
        $loanApplications = LoanApplication::where('status', 'incomplete')
            ->whereDate('created_at', '<', $oneDayAgo)
            ->get();

        // Retrieve loan applications that are to be declined and created more than 2 days ago
        $declinedLoanApplications = LoanApplication::whereIn('status', ['incomplete', 'pending'])
            ->whereDate('created_at', '<', $twoDaysAgo)
            ->get();

        foreach ($loanApplications as $loanApplication) {
            // Extract user and loan details
            $user = $loanApplication->user;
            $loanApplicationId = $loanApplication->id;

            $this->sendReminder($user, $loanApplicationId);

            if ($user) {
                $this->taskLog($user, $loanApplicationId, 'Reminder', 'Reminder sent');
            }

        }

        foreach ($declinedLoanApplications as $loanApplication) {
            // Extract user and loan details

            $user = $loanApplication->user;
            $loanApplicationId = $loanApplication->id;
            $loanApplication->status = 'Declined';
            $loanApplication->rejection_reason = 'Inactive for more than 2 days';
            $loanApplication->save();

        }

        $this->info('Notificaiton has been sent to ' . count($loanApplications) . ' loan applications created more than 1 day ago');
    }

    function sendReminder($user, $loanApplicationId)
    {
        $datas = array(
            array(
                'template' => 'application-completion-reminder',
                'type' => 'mail',
            ),
            array(
                'subject' => 'Complete application',
                'content' => 'Please finalize your application for uninterrupted service. Thank you!',
                'type' => 'inapp',
            )
        );

        foreach ($datas as $d) {
            if (isset($user->id)) {
                $d['user_id'] = $user->id;
                $d['loan_application_id'] = $loanApplicationId;
                $this->storeMsg($d);
            }
        }

        //Send Database Notification
        $notification['icon'] = "ti-mail";
        $notification['color'] = "warning";
        $notification['heading'] = "Complete application";
        $notification['msg'] = "Please finalize your application for uninterrupted service. Thank you!";

        if (isset($user->id)) {
            $this->sendNotification($user->id, $notification);
        }
    }
}
