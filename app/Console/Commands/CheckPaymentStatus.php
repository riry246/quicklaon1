<?php

namespace App\Console\Commands;

use App\Models\LoanApplication;
use App\Models\LoanStatement;
use App\Models\Transaction;
use App\Traits\GeneralTrait;
use App\Traits\LoanTrait;
use App\Traits\MessageTrait;
use App\Traits\MonoovaTrait;
use Illuminate\Console\Command;

class CheckPaymentStatus extends Command
{

    use MonoovaTrait, LoanTrait, MessageTrait, GeneralTrait;
    protected $signature = 'check:payment-status';
    protected $description = 'Check Payment Status of Monnova payments';

    protected $payment_status = null;
    protected $payment_status_description = null;


    public function handle()
    {
        $transactions = Transaction::where('status', 'WaitingOnClearedFunds')->get();

        foreach ($transactions as $transaction) {
            
            sleep(30);
            $paymentStatus = $this->checkStatus($transaction->caller_unique_reference);

            $transaction->status_description = $paymentStatus['statusDescription'];
            $transaction->status = $paymentStatus['transactionStatus'];
            $transaction->completed_date = $paymentStatus['completedDate'];
            $transaction->credit_card_payment_status = $paymentStatus['creditCardPaymentStatus'];
            $transaction->dishonoured_date = $paymentStatus['dishonouredDate'];
            $transaction->expected_clearance_date_for_funds = $paymentStatus['expectedClearanceDateForFunds'];
            $transaction->funds_cleared_date = $paymentStatus['fundsClearedDate'];
            $transaction->save();

            if ($transaction->loan_statements_id) {
                $statement = LoanStatement::find($transaction->loan_statements_id);
                $statement->payment_status = $paymentStatus['transactionStatus'];
                $statement->save();
            }

            $application = LoanApplication::find($transaction->application_id);

            $this->payment_status = $transaction->status;

            //Manage Dishonoured Payment Notification
            if ($transaction->status == 'Dishonoured') {

                $this->payment_status_description = "There's been a Direct Debit dishonour on your CashFaster Loan Contract.";

                $dishornedPayments = $application->dishournedStatement;
                $user = $application->user;
                if (count($dishornedPayments) === 1) {
                    $this->sendReminder($user, $application, '1st-direct-debit-dishourned');
                } else if (count($dishornedPayments) === 2) {
                    $this->sendReminder($user, $application, '2nd-dishonour-1st-fee-charged');
                } else {
                    $this->sendReminder($user, $application, '3rd-dishonour-serious');
                }

                $loanStatement = LoanStatement::find($transaction->loan_statements_id);
                $this->createReschedulePayment($loanStatement);

                $this->taskLog($user, $transaction->loan_application_id, 'Payment', 'Dishourned Payment');
            }

        }

        $this->info('Payment status check completed');
    }

    function sendReminder($user, $loanApplication, $template)
    {

        $account = $user->monoovaAccount;

        $datas = array(
            array(
                'template' => $template,
                'type' => 'mail',
            ),
            array(
                'type' => 'inapp',
                'template' => $template,
            ),
            array(
                'type' => 'sms',
                'template' => $template,
            )
        );

        foreach ($datas as $d) {
            $d['user_id'] = $user->id;
            $d['loan_application_id'] = $loanApplication->id;
            $d['bankAccountName'] = $account->bankAccountName;
            $d['bankAccountNumber'] = $account->bankAccountNumber;
            $d['bsb'] = $account->bsb;
            $d['payid'] = $account->payid;
            $this->storeMsg($d);
        }

        //Send Database Notification
        $notification['icon'] = "ti-mail";
        $notification['color'] = "warning";
        $notification['heading'] = $this->payment_status;
        $notification['msg'] = $this->payment_status_description;

        $this->sendNotification($user->id, $notification);

        $notification['msg'] = $user->first_name . ':' . $this->payment_status_description;
        $this->sendNotification('2', $notification);
    }
}
