<?php

namespace App\Traits;

use App\Models\BankAccount;
use App\Models\LoanApplication;
use App\Models\LoanStatement;
use App\Models\User;
use App\Models\UserAttr;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


trait LoanTrait
{
    public function generateLoanStatement($loanAmount, $durationInWeeks, $loanApprovalDate, $frequency, $settlement_day)
    {
        $statements = [];

        $repayment_frequency = 1;

        if ($frequency == 'fortnightly') {
            $repayment_frequency = 2;
        }

        $repayment_dates = $durationInWeeks / $repayment_frequency;

        $weeklyInterestRate = 0.04 / 4;
        $totalintrest = $weeklyInterestRate * $durationInWeeks;

        $establishmentFee = $loanAmount * 0.2;
        $weeklyEstablishmentFeePaid = $establishmentFee / ($durationInWeeks / $repayment_frequency);

        $principal = $establishmentFee + $loanAmount;
        $principal = $loanAmount * $totalintrest + $principal;
        $weeklyPayment = $principal / ($durationInWeeks / $repayment_frequency);
        $approvalDate = Carbon::parse($loanApprovalDate);
        

        for ($week = 1; $week <= $repayment_dates; $week++) {
            // Calculate payment date
            $paymentDate = $approvalDate->addWeeks($repayment_frequency);

            // Calculate settlement date based on provided day
            $settlementDate = $settlement_day ? $this->calculateSettlementDate($paymentDate, $settlement_day) : null;

            $interest = $loanAmount * ($weeklyInterestRate * $repayment_frequency) + $weeklyEstablishmentFeePaid;

            $principalPayment = $weeklyPayment - $interest;
            $payableWeekly = $weeklyPayment;
            $closingBalance = $principal - $weeklyPayment;

            $status = $this->determinePaymentStatus($paymentDate->toDateString());

            $statements[] = [
                'payment_date' => $paymentDate->toDateString(),
                'settlement_date' => $settlementDate ? $settlementDate->toDateString() : null,
                'opening_balance' => number_format($principal, 2, '.', ''),
                'weekly_payment' => number_format($payableWeekly, 2, '.', ''),
                'interest' => number_format($interest, 2, '.', ''),
                'principal_payment' => number_format($principalPayment, 2, '.', ''),
                'closing_balance' => number_format($closingBalance, 2, '.', ''),
                'payment_status' => $status,
                'weekly_establishment_fee' => number_format($weeklyEstablishmentFeePaid, 2, '.', ''),
                'weekly_interest' => number_format($interest - $weeklyEstablishmentFeePaid, 2, '.', ''), // Assuming interest includes the establishment fee
            ];

            $principal = $closingBalance;
        }

        return $statements;
    }


    // Helper function to calculate settlement date based on the provided day
    private function calculateSettlementDate($date, $settlement_day)
    {
        $settlementDate = clone $date;

        // Set the day of the week directly using modify
        $settlementDate->modify('next ' . ucfirst($settlement_day));

        return $settlementDate;


    }
    protected function determinePaymentStatus($paymentDate)
    {
        $currentDate = Carbon::now();
        $paymentDueDate = Carbon::parse($paymentDate);

        if ($currentDate->gt($paymentDueDate) < $currentDate->diffInWeeks($paymentDueDate, false)) {
            return 'Scheduled';
        } elseif ($currentDate->gt($paymentDueDate)) {
            return 'Late Payment';
        } elseif ($currentDate->eq($paymentDueDate)) {
            return 'Paid on Time';
        }

        return 'Upcoming Payment';
    }

    public function getActiveBank($app_id)
    {

        $accounts = DB::table('bank_accounts')
            ->join('banks', 'bank_accounts.basiq_code', '=', 'banks.basiq_code')
            ->select('bank_accounts.*', 'banks.name as bank_name', 'banks.logo as img')
            ->where('application_id', $app_id)
            ->latest('created_at')
            ->first();

        //get Account info
        if ($accounts) {
            $accounts->account_info = '';
            $data = json_decode($accounts->statements, true);

            foreach ($data as $d) {
                if ($accounts->primary_account == $d['accountNo']) {
                    $accounts->account_info = $d['name'];
                }
            }

        }

        return $accounts;
    }

    public function getUserAttrValue($application_id, $attr)
    {
        $attribute = UserAttr::where('application_id', $application_id)
            ->where('attr', $attr)->first();

        if ($attribute) {
            return $attribute->value;
        }

        return null;
    }

    public function createReschedulePayment($loan)
    {
        $reschedule_fee = 10;
        $late_fee = 0;
        $status = 'crs';
        $total = 0;

        $loanApplication = LoanApplication::find($loan->loan_application_id);
        $settlememt_date = $loanApplication->geLastSettlementDate()->first();
        $settlememt_date = $settlememt_date->settlement_date;

        if ($loan->payment_status == 'Dishonoured') {
            $reschedule_fee = 0;
            $late_fee = 35;
            $status = 'Dishonoured';
            //Weekly payment calculation
            if ($loan->late_fee > 0) {
                $total = $loan->weekly_payment;
            } else {
                $total = $loan->weekly_payment + $late_fee;
            }
        } else {
            $total = $loan->weekly_payment + $reschedule_fee + $late_fee;
        }


        $loanStatement = new LoanStatement();
        $loanStatement->loan_application_id = $loan->loan_application_id;
        $loanStatement->opening_balance = $loan->opening_balance;
        $loanStatement->weekly_payment = $total; // Adjusted to add reschedule_fee
        $loanStatement->interest = $loan->interest;
        $loanStatement->principal_payment = $loan->principal_payment;
        $loanStatement->closing_balance = $loan->closing_balance;
        $loanStatement->payment_date = (new DateTime($settlememt_date))->modify('+1 week')->format('Y-m-d');
        $loanStatement->settlement_date = (new DateTime($settlememt_date))->modify('+1 week')->format('Y-m-d');
        $loanStatement->payment_status = 'Re-scheduled';
        $loanStatement->late_fee = $late_fee;
        $loanStatement->reschedule_fee = $reschedule_fee; // Added the reschedule_fee
        $loanStatement->parent_id = $loan->id;
        $loanStatement->frequency = $loan->frequency;

        $loanStatement->save();

        //Update old statement
        $loan->payment_status = $status;
        $loan->save();

        return;

    }

    public function createReschedulePaymentManually($loan, $payment_status, $settlement_date)
    {
        $reschedule_fee = 10;
        $late_fee = 0;
        $status = 'crs';
        $total = 0;

        if ($payment_status == 'Dishonoured&Rescheduled') {
            $reschedule_fee = 0;
            $late_fee = 35;
            $status = 'Dishonoured';
            //Weekly payment calculation
            if ($loan->late_fee > 0) {
                $total = $loan->weekly_payment;
            } else {
                $total = $loan->weekly_payment + $late_fee;
            }
        } else {
            $total = $loan->weekly_payment + $reschedule_fee + $late_fee;
        }


        $loanStatement = new LoanStatement();
        $loanStatement->loan_application_id = $loan->loan_application_id;
        $loanStatement->opening_balance = $loan->opening_balance;
        $loanStatement->weekly_payment = $total;
        $loanStatement->interest = $loan->interest;
        $loanStatement->principal_payment = $loan->principal_payment;
        $loanStatement->closing_balance = $loan->closing_balance;
        $loanStatement->payment_date = $loan->payment_date;
        $loanStatement->settlement_date = (new DateTime($settlement_date))->format('Y-m-d');
        $loanStatement->payment_status = 'Re-scheduled';
        $loanStatement->late_fee = $late_fee;
        $loanStatement->reschedule_fee = $reschedule_fee; // Added the reschedule_fee
        $loanStatement->parent_id = $loan->id;
        $loanStatement->frequency = $loan->frequency;

        $loanStatement->save();

        //Update old statement
        $loan->payment_status = $status;
        $loan->save();

        return;

    }
    public function createPartialPaymentManually($loan, $amount, $settlement_date)
    {
        $loanStatement = new LoanStatement();
        $loanStatement->loan_application_id = $loan->loan_application_id;
        $loanStatement->opening_balance = $loan->opening_balance;
        $loanStatement->weekly_payment = $amount;
        $loanStatement->interest = $loan->interest;
        $loanStatement->principal_payment = $loan->principal_payment;
        $loanStatement->closing_balance = $loan->closing_balance;
        $loanStatement->payment_date = $loan->payment_date;
        $loanStatement->settlement_date = (new DateTime($settlement_date))->format('Y-m-d');
        $loanStatement->payment_status = 'Re-scheduled';
        $loanStatement->parent_id = $loan->id;
        $loanStatement->frequency = $loan->frequency;

        $loanStatement->save();

        return;

    }
    public function getStatement($loanAmount, $durationInWeeks, $frequency)
    {
        $statements = [];

        $repayment_frequency = 1;

        if ($frequency == 'fortnightly') {
            $repayment_frequency = 2;
        }

        $repayment_dates = $durationInWeeks / $repayment_frequency;

        $weeklyInterestRate = 0.04 / 4;
        $totalintrest = $weeklyInterestRate * $durationInWeeks * $loanAmount;
        $montlyintrest = $weeklyInterestRate * 4 * $loanAmount;

        $payabl_settlement = $weeklyInterestRate * 4 * $loanAmount;


        $establishmentFee = $loanAmount * 0.2;

        $repayment_amount = ($totalintrest + $establishmentFee + $loanAmount) / $repayment_dates;
        $totalFee = $totalintrest + $establishmentFee;
        $totalFeeMontly = $montlyintrest + $establishmentFee;
        $min_repayment_amount = $montlyintrest + $establishmentFee + $loanAmount;
        $totalFee = $totalintrest + $establishmentFee;

        $statement = array(
            'repayment' => $repayment_dates,
            'totalintrest' => $totalintrest,
            'establishmentFee' => $establishmentFee,
            'repayment_amount' => $repayment_amount,
            'montlyintrest' => $montlyintrest,
            'min_repayment_amount' => $min_repayment_amount,
            'totalFeeMontly' => $totalFeeMontly,
            'totalFee' => $totalFee,
            'payabl_settlement' => $totalFee - $totalFeeMontly
        );
        return $statement;
    }


    public function totalPayableAmount($loanid)
    {

        $loanApplication = LoanApplication::find($loanid);
        $unpaidloanstatement = $loanApplication->unpaidstatement;

        $sum = 0;
        foreach ($unpaidloanstatement as $up) {
            $sum = $sum + $up->weekly_payment;
        }
        // return 1;
        return number_format($sum, 2, '.', '');

    }
    public function completedStatement($loanid)
    {

        $loanApplication = LoanApplication::find($loanid);
        $paidloanstatement = $loanApplication->paidstatement;
        return count($paidloanstatement);

    }

    public function reGenerateLoanStatement($loanAmount, $durationInWeeks, $date, $frequency)
    {
        $statements = [];

        $repayment_frequency = 1;

        if ($frequency == 'fortnightly') {
            $repayment_frequency = 2;
        }

        $repayment_dates = $durationInWeeks / $repayment_frequency;

        $weeklyInterestRate = 0.04 / 4;
        $totalintrest = $weeklyInterestRate * $durationInWeeks;

        $establishmentFee = $loanAmount * 0.2;
        $weeklyEstablishmentFeePaid = $establishmentFee / ($durationInWeeks / $repayment_frequency);

        $principal = $establishmentFee + $loanAmount;
        $principal = $loanAmount * $totalintrest + $principal;
        $weeklyPayment = $principal / ($durationInWeeks / $repayment_frequency);
        $approvalDate = Carbon::parse($date)->subWeek();
        if ($frequency == 'fortnightly') {
            $approvalDate = Carbon::parse($date)->subWeek(2);
        }

        for ($week = 1; $week <= $repayment_dates; $week++) {
            // Calculate payment date
            $paymentDate = $approvalDate->addWeeks($repayment_frequency);

            // Calculate settlement date based on provided day
            $settlementDate = $paymentDate;

            $interest = $loanAmount * ($weeklyInterestRate * $repayment_frequency) + $weeklyEstablishmentFeePaid;

            $principalPayment = $weeklyPayment - $interest;
            $payableWeekly = $weeklyPayment;
            $closingBalance = $principal - $weeklyPayment;

            $status = $this->determinePaymentStatus($paymentDate->toDateString());

            $statements[] = [
                'payment_date' => $paymentDate->toDateString(),
                'settlement_date' => $settlementDate ? $settlementDate->toDateString() : null,
                'opening_balance' => number_format($principal, 2, '.', ''),
                'weekly_payment' => number_format($payableWeekly, 2, '.', ''),
                'interest' => number_format($interest, 2, '.', ''),
                'principal_payment' => number_format($principalPayment, 2, '.', ''),
                'closing_balance' => number_format($closingBalance, 2, '.', ''),
                'payment_status' => $status,
                'weekly_establishment_fee' => number_format($weeklyEstablishmentFeePaid, 2, '.', ''),
                'weekly_interest' => number_format($interest - $weeklyEstablishmentFeePaid, 2, '.', ''), // Assuming interest includes the establishment fee
            ];

            $principal = $closingBalance;
        }

        return $statements;
    }

    public function settleStatement($id)
    {
        LoanStatement::where('loan_application_id', $id)
            ->whereIn('payment_status', ['Scheduled', 'Re-scheduled'])
            ->update([
                'settlement_date' => now(),
                'payment_status' => 'Complete',
            ]);

        return;
    }

    public function getStatementTotalByStatus($loan_id, $statuses)
    {
        $total = LoanStatement::where('loan_application_id', $loan_id)
            ->whereIn('payment_status', $statuses)
            ->sum('weekly_payment');

        return $total;
    }

    public function updateStatement($loan_id, $statuses, $status)
    {
        $statements = LoanStatement::where('loan_application_id', $loan_id)
            ->whereIn('payment_status', $statuses)
            ->get();

        foreach ($statements as $statement) {
            $statement->payment_status = $status;
            $statement->save();
        }

        return;
    }

    public function checkApplicationEndDate($app)
    {
        if (!$app->maturity_date) {
            $frequency = $app->frequency;
            $durationInWeeks = $app->duration;
            $loanApprovalDate = $app->approved_date;
            $settlement_day = $this->getUserAttrValue($app->id, 'pay_day') ?: 'tuesday';

            $repayment_frequency = ($frequency == 'fortnightly') ? 2 : 1;
            $repayment_dates = $durationInWeeks / $repayment_frequency;

            $approvalDate = Carbon::parse($loanApprovalDate);

            $paymentDate = $approvalDate->addWeeks($repayment_dates * $repayment_frequency);
            $settlementDate = $settlement_day ? $this->calculateSettlementDate($paymentDate, $settlement_day) : null;

            $app->maturity_date = $settlementDate;
            $app->save();

        } else {
            $settlementDate = $app->maturity_date;
        }


        return $settlementDate;
    }

    public function checkOverDuePayments($app)
    {
        $statement = LoanStatement::where('loan_application_id', $app->id)
            ->where('settlement_date', '>', $app->maturity_date)
            ->whereIn('payment_status', ['Scheduled', 'Re-scheduled'])
            ->get();

        return $statement;

    }

    public function getInterest($app)
    {
        $frequency = $app->frequency;
        $durationInWeeks = $app->duration;
        $loanAmount = $app->approved_amount;
        $repayment_frequency = ($frequency == 'fortnightly') ? 2 : 1;

        $weeklyInterestRate = 0.04 / 4;
        $totalinterest = $weeklyInterestRate * $durationInWeeks;

        $interest = $loanAmount * $totalinterest;
        $weeklyInterestRate = $interest / $durationInWeeks * $repayment_frequency;

        $establishmentFee = $loanAmount * 0.2;
        $weeklyEstablishmentFeePaid = $establishmentFee / ($durationInWeeks / $repayment_frequency);

        return [
            'interest' => $weeklyInterestRate,
            'establishmentFee' => $weeklyEstablishmentFeePaid,
        ];
    }
}