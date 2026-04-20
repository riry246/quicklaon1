<?php

namespace App\Console\Commands;

use App\Models\LoanApplication;
use App\Models\LoanStatement;
use App\Traits\BasiqTrait;
use App\Traits\GeneralTrait;
use App\Traits\LoanTrait;
use App\Traits\MessageTrait;
use App\Traits\MonoovaTrait;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckLoanPayments extends Command
{
    use MonoovaTrait, LoanTrait, MessageTrait, GeneralTrait, BasiqTrait;
    protected $signature = 'check:loan-payments';
    protected $description = 'Check for loan payment dates and make direct payments';
    protected $payment_status = null;
    protected $dishourned_possibility = null;
    protected $payment_status_description = null;


    public function handle()
    {
        $today = Carbon::now()->toDateString();

        $loanStatement = LoanStatement::whereIn('payment_status', ['Scheduled', 'Upcoming Payment','Re-scheduled'])
            ->whereDate('settlement_date', '=', $today)
            ->get();

        foreach ($loanStatement as $statement) {

            $instalment = LoanStatement::findOrFail($statement->id);
            $loan = LoanApplication::findOrFail($instalment->loan_application_id);

            $illionPrimaryAccount = $loan->illionPrimaryAccount;
            $bank_account = $loan->activeBankAccounts;
            $amount = $instalment->weekly_payment;

            if ($illionPrimaryAccount) {
                $transaction = $this->directDebitIllion($illionPrimaryAccount, $loan, $instalment);
            } else {
                $targetAccountNo = $bank_account->primary_account;
                $transaction = $this->directDebit($targetAccountNo, $loan, $instalment);
            }

            if ($transaction) {
                $instalment->payment_status = $transaction->status;
                $instalment->transaction_id = $transaction->id;
                $instalment->save();

            }

            sleep(30);

        }

        $this->info("Check Payment Exceuted successfully: Number of transaction:" . count($loanStatement));
    }

    public function saveTransactionStatus($transaction, $instalment)
    {
        $instalment->payment_status = $this->payment_status;
        $instalment->dishourned_possibility = $this->dishourned_possibility;
        $instalment->transaction_id = $transaction->id ?? 0;
        $instalment->save();

        return;

    }
    private function findAccountByAccountNo($accounts, $targetAccountNo)
    {
        foreach ($accounts as $account) {
            if ($account['accountNo'] === $targetAccountNo) {
                return $account;
            }
        }

        return null;
    }

    private function findAccountWithHigherFunds($accounts, $minimumAvailableFunds)
    {
        foreach ($accounts as $account) {
            if ($account['availableFunds'] > $minimumAvailableFunds) {
                return $account;
            }
        }

        return null;
    }

    private function paymentSuccess($message)
    {
        return redirect()->back()->with('success', $message);
    }

    private function paymentError($application)
    {
        $this->payment_status_description = "There's been a Direct Debit dishonour on your CashFaster Loan Contract.";

        $dishornedPayments = $application->dishournedStatement;

        $user = $application->user;
        if ($dishornedPayments) {
            if (count($dishornedPayments) === 3) {
                $this->sendReminder($user, $application, '1st-direct-debit-Dishonoured');

            } else if (count($dishornedPayments) === 2) {
                $this->sendReminder($user, $application, '2nd-dishonour-1st-fee-charged');
            } else {
                $this->sendReminder($user, $application, '3rd-dishonour-serious');
            }

        }
        $this->taskLog($user, $application->id, 'Payment', 'Dishonoured Payment');

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
