<?php

namespace App\Traits;

use App\Models\LoanApplication;
use App\Models\LoanStatement;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait ReportDetailTrait
{

    public function generateDateWiseLoanBookValueReport($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $report = [];
        $totalPrincipleAmount = 0;
        $totalLoans = 0;

        // Loop through each date within the date range
        while ($startDate->lte($endDate)) {
            // Get the loans created on the current date
            $loans = LoanApplication::whereDate('approved_date', $startDate->toDateString())
                ->whereIn('status', ['active', 'completed'])
                ->get();

            $totalLoanValue = $loans->sum('approved_amount');
            $totalNumberOfLoans = $loans->count();

            // Add the loans, total loan value, and total number of loans for the current date to the report
            $report[$startDate->toDateString()] = [
                'loans' => $loans,
                'total_loan_value' => $totalLoanValue,
                'total_number_of_loans' => $totalNumberOfLoans
            ];

            $totalPrincipleAmount = $totalPrincipleAmount + $totalLoanValue;
            $totalLoans = $totalLoans + $totalNumberOfLoans;

            // Move to the next date
            $startDate->addDay();
        }

        return [
            'report' => $report,
            'totalAmounts' => $totalPrincipleAmount,
            'totalLoans' => $totalLoans,
        ];
    }


    public function generateDateWiseRevenueReport($startDate, $endDate)
    {
        // Parse start and end dates using Carbon
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $report = [];
        $grandtotalRevenue = 0;
        $grandtotalNumberOfTransactions = 0;
        $grandtotalWeeklyInterest = 0;
        $grandtotalPrincipalPayment = 0;
        $grandtotalWeeklyEstablishmentFee = 0;
        $grandtotalLateFee = 0;
        $grandtotalRescheduleeFee = 0;
        $grandtotalOverdueInterest = 0;
        $grandtotalSuccessfullTransaction = 0;
        $grandtotalDishonouredTransaction = 0;
        $grandtotalWaitingOnClearedFundsTransaction = 0;
        $grandpercentage = 0;

        $totalSuccessfullWeeklyInterest = 0;
        $totalSuccessfullPrincipalPayment = 0;
        $totalSuccessfullWeeklyEstablishmentFee = 0;
        $totalSuccessfullLateFee = 0;
        $totaSuccessfulllRescheduleeFee = 0;
        $totalSuccessfullOverdueInterest = 0;

        // Loop through each date within the date range
        while ($startDate->lte($endDate)) {
            // Format the date as per your requirement (e.g., 'Y-m-d')
            $formattedDate = $startDate->toDateString();

            // Query the database to get the transactions for the current date
            $transactions = Transaction::whereDate('updated_at', $formattedDate)
                ->whereNot('account_number', '802985425628199')
                ->where('type', 'Credit')
                ->get();

            $transactionData = [];
            $totalRevenue = 0;
            $totalNumberOfTransactions = 0;
            $totalWeeklyInterest = 0;
            $totalPrincipalPayment = 0;
            $totalWeeklyEstablishmentFee = 0;
            $totalLateFee = 0;
            $totalRescheduleeFee = 0;
            $totalOverdueInterest = 0;
            $totalSuccessfullTransaction = 0;
            $totalDishonouredTransaction = 0;
            $totalWaitingOnClearedFundsTransaction = 0;
            $percentage = 0;





            // Iterate through each transaction
            foreach ($transactions as $transaction) {
                // Retrieve the statement related to the transaction
                $statements = $transaction->statements;

                // Add the statement data to the transaction data
                $transactionData[] = [
                    'transaction' => $transaction,
                    'statement' => $statements,
                ];
                foreach ($statements as $statement) {
                    // Sum up the values
                    $totalWeeklyInterest += $statement->interest;
                    $totalPrincipalPayment += $statement->principal_payment;
                    $totalWeeklyEstablishmentFee += $statement->weekly_establishment_fee;
                    $totalLateFee += $statement->late_fee;
                    $totalRescheduleeFee += $statement->reschedule_fee;
                    $totalOverdueInterest += $statement->overdue_interest;


                    if ($statement->payment_status == 'Complete') {
                        $totalSuccessfullWeeklyInterest += $statement->interest;
                        $totalSuccessfullPrincipalPayment += $statement->principal_payment;
                        $totalSuccessfullWeeklyEstablishmentFee += $statement->weekly_establishment_fee;
                        $totalSuccessfullLateFee += $statement->late_fee;
                        $totaSuccessfulllRescheduleeFee += $statement->reschedule_fee;
                        $totalSuccessfullOverdueInterest += $statement->overdue_interest;
                    }

                    //grandtotal
                    $grandtotalWeeklyInterest += $statement->interest;
                    $grandtotalPrincipalPayment += $statement->principal_payment;
                    $grandtotalWeeklyEstablishmentFee += $statement->weekly_establishment_fee;
                    $grandtotalLateFee += $statement->late_fee;
                    $grandtotalRescheduleeFee += $statement->reschedule_fee;
                    $grandtotalOverdueInterest += $statement->overdue_interest;

                }



                if ($transaction->status == 'Complete') {
                    $totalSuccessfullTransaction += $transaction->amount;
                    $grandtotalSuccessfullTransaction += $transaction->amount;
                } elseif ($transaction->status == 'Dishonoured') {
                    $totalDishonouredTransaction += $transaction->amount;
                    $grandtotalDishonouredTransaction += $transaction->amount;
                } elseif ($transaction->status == 'WaitingOnClearedFunds') {
                    $totalWaitingOnClearedFundsTransaction += $transaction->amount;
                    $grandtotalWaitingOnClearedFundsTransaction += $transaction->amount;
                }
            }

            // Calculate total revenue and total number of transactions
            $totalRevenue = $transactions->sum('amount');
            $totalNumberOfTransactions = $transactions->count();

            if ($totalSuccessfullTransaction > 0) {
                $percentage = $totalSuccessfullTransaction / $totalRevenue * 100;
            }


            $grandtotalRevenue += $transactions->sum('amount');
            $grandtotalNumberOfTransactions += $transactions->count();

            // Add the total revenue for the current date to the report
            $report[$formattedDate] = [
                'transactions' => $transactionData,
                'totalRevenue' => $totalRevenue,
                'totalNumberOfTransactions' => $totalNumberOfTransactions,
                'totalWeeklyInterest' => $totalWeeklyInterest,
                'totalPrincipalPayment' => $totalPrincipalPayment,
                'totalWeeklyEstablishmentFee' => $totalWeeklyEstablishmentFee,
                'totalLateFee' => $totalLateFee,
                'totalRescheduleeFee' => $totalRescheduleeFee,
                'totalOverdueInterest' => $totalOverdueInterest,
                'totalSuccessfullTransaction' => $totalSuccessfullTransaction,
                'totalDishonouredTransaction' => $totalDishonouredTransaction,
                'totalWaitingOnClearedFundsTransaction' => $totalWaitingOnClearedFundsTransaction,
                'totalpercentage' => $percentage
            ];

            // Move to the next date
            $startDate->addDay();
        }

        if ($grandtotalSuccessfullTransaction > 0) {
            $grandpercentage = $grandtotalSuccessfullTransaction / $grandtotalRevenue * 100;
        }

        return [
            'report' => $report,
            'totalRevenue' => $grandtotalRevenue,
            'totalNumberOfTransactions' => $grandtotalNumberOfTransactions,
            'totalWeeklyInterest' => $grandtotalWeeklyInterest,
            'totalPrincipalPayment' => $grandtotalPrincipalPayment,
            'totalWeeklyEstablishmentFee' => $grandtotalWeeklyEstablishmentFee,
            'totalLateFee' => $grandtotalLateFee,
            'totalRescheduleeFee' => $grandtotalRescheduleeFee,
            'totalOverdueInterest' => $grandtotalOverdueInterest,
            'totalSuccessfullTransaction' => $grandtotalSuccessfullTransaction,
            'totalDishonouredTransaction' => $grandtotalDishonouredTransaction,
            'totalWaitingOnClearedFundsTransaction' => $grandtotalWaitingOnClearedFundsTransaction,
            'totalpercentage' => $grandpercentage,

            'totalSuccessfullWeeklyInterest' => $totalSuccessfullWeeklyInterest,
            'totalSuccessfullPrincipalPayment' => $totalSuccessfullPrincipalPayment,
            'totalSuccessfullWeeklyEstablishmentFee' => $totalSuccessfullWeeklyEstablishmentFee,
            'totalSuccessfullLateFee' => $totalSuccessfullLateFee,
            'totalSuccessfullRescheduleeFee' => $totaSuccessfulllRescheduleeFee,
            'totalSuccessfullOverdueInterest' => $totalSuccessfullOverdueInterest,


        ];


    }

    public function generateDateWiseTransactionReport($startDate, $endDate)
    {
        // Parse start and end dates using Carbon
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $report = [];
        $grandTotal = 0;
        $grandTotalCredit = 0;
        $grandTotalDebit = 0;
        $grandTotalNumberOfTransactions = 0;
        $totalWaitingOnClearedFunds = 0;
        $totalDishonoured = 0;

        // Loop through each date within the date range
        while ($startDate->lte($endDate)) {
            // Format the date as per your requirement (e.g., 'Y-m-d')
            $formattedDate = $startDate->toDateString();

            // Query the database to get the transactions for the current date
            $transactions = Transaction::whereDate('updated_at', $formattedDate)
                ->get();

            $transactionData = [];

            $total = 0;
            $totalNumberOfTransactions = 0;
            $totalCredit = 0;
            $totalDebit = 0;

            // Iterate through each transaction
            foreach ($transactions as $transaction) {

                $transactionData[] = $transaction;

                if ($transaction->type == 'Credit') {
                    $totalCredit += $transaction->amount;

                } else {
                    $totalDebit += $transaction->amount;
                    $grandTotalDebit += $transaction->amount;
                }

                if ($transaction->status == 'WaitingOnClearedFunds') {
                    $totalWaitingOnClearedFunds += $transaction->amount;
                } elseif ($transaction->status == 'Dishonoured') {
                    $totalDishonoured += $transaction->amount;
                } else {
                    if ($transaction->type == 'Credit') {

                        $grandTotalCredit += $transaction->amount;
                    }
                }
            }

            // Calculate total revenue and total number of transactions
            $total = $transactions->sum('amount');
            $totalNumberOfTransactions = $transactions->count();

            $grandTotal += $total;
            $grandTotalNumberOfTransactions += $totalNumberOfTransactions;

            // Add the total revenue for the current date to the report
            $report[$formattedDate] = [
                'transactions' => $transactionData,
                'total' => $total,
                'totalNumberOfTransactions' => $totalNumberOfTransactions,
                'totalCredit' => $totalCredit,
                'totalDebit' => $totalDebit,
            ];

            // Move to the next date
            $startDate->addDay();
        }

        return [
            'report' => $report,
            'grandTotal' => $grandTotal,
            'totalNumberOfTransactions' => $grandTotalNumberOfTransactions,
            'grandTotalCredit' => $grandTotalCredit,
            'grandTotalDebit' => $grandTotalDebit,
            'totalWaitingOnClearedFunds' => $totalWaitingOnClearedFunds,
            'totalDishonoured' => $totalDishonoured,
        ];
    }

    public function generateArrearReportDateWise($startDate, $endDate)
    {
        // Parse start and end dates using Carbon
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $report = [];
        $totalDishonoredAmount = 0;
        $totalActiveLoansInArrears = 0;

        // Loop through each date within the date range
        while ($startDate->lte($endDate)) {
            $formattedDate = $startDate->format('Y-m-d');

            // Get the loans with dishonored payments for the current date
            $activeLoansInArrears = LoanApplication::whereHas('statements', function ($query) use ($formattedDate) {
                $query->where('payment_status', 'Dishonoured')
                    ->whereDate('updated_at', $formattedDate);
            })->get();

            // Initialize variables to calculate dishonored amount and loan count for the current date
            $dishonoredAmount = 0;
            $activeLoansInArrearsCount = $activeLoansInArrears->count();

            // Initialize an array to store loan data including dishonored statements
            $loanData = [];

            foreach ($activeLoansInArrears as $loan) {
                // Get dishonored statements for the current loan
                $dishonoredStatements = $loan->statements()->where('payment_status', 'Dishonoured')
                    ->whereDate('updated_at', $formattedDate)->get();

                foreach ($dishonoredStatements as $s) {

                }
                $dishonoredAmount += $dishonoredStatements->sum('weekly_payment');

                // Add loan data along with dishonored statements to the array
                $loanData[] = [
                    'loan' => $loan,
                    'dishonored_statements' => $dishonoredStatements
                ];
            }

            // Update total dishonored amount and total active loans in arrears
            $totalDishonoredAmount += $dishonoredAmount;
            $totalActiveLoansInArrears += $activeLoansInArrearsCount;

            // Add the arrear count, dishonored amount, and loan data to the report
            $report[$formattedDate] = [
                'count' => $activeLoansInArrearsCount,
                'amount' => $dishonoredAmount,
                'loan_data' => $loanData
            ];

            // Move to the next date
            $startDate->addDay();
        }

        return [
            'report' => $report,
            'totalDishonoredAmount' => $totalDishonoredAmount,
            'totalActiveLoansInArrears' => $totalActiveLoansInArrears
        ];
    }

    public function generateProjectedCashFlowDateWise($startDate, $endDate)
    {
        // Parse start and end dates using Carbon
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $report = [];
        $totalWeeklyPayment = 0;
        $totalWeeklyInterest = 0;
        $totalPrincipalPayment = 0;
        $totalRescheduleFee = 0;
        $totalDishonoredFee = 0;
        $totalWeeklyEstablishmentFee = 0;
        $totalCompletePayment = 0;
        $totalPendingPayment = 0;

        // Loop through each date within the date range
        while ($startDate->lte($endDate)) {
            $formattedDate = $startDate->format('Y-m-d');

            // Get transactions for the current date excluding certain payment statuses
            $transactions = LoanStatement::where('settlement_date', $formattedDate)
                ->whereNotIn('payment_status', ['crs', 'Hold', 'Canceled'])
                ->get();

            if (count($transactions) > 0) {
                // Initialize variables for various fees and transactions
                $weeklyPayment = 0;
                $weeklyInterest = 0;
                $principalPayment = 0;
                $rescheduleFee = 0;
                $dishonoredFee = 0;
                $weeklyEstablishmentFee = 0;
                $completePayment = 0;
                $pendingPayment = 0;
                $dailyTransactions = [];

                // Calculate totals for the current date
                foreach ($transactions as $transaction) {
                    $dailyTransactions[] = $transaction;
                    $weeklyPayment += $transaction->weekly_payment;
                    $weeklyInterest += $transaction->weekly_interest;
                    $principalPayment += $transaction->principal_payment;
                    $rescheduleFee += $transaction->reschedule_fee;
                    $weeklyEstablishmentFee += $transaction->weekly_establishment_fee;

                    if ($transaction->payment_status == 'Complete') {
                        $completePayment += $transaction->weekly_payment;
                    }
                    if ($transaction->payment_status == 'WaitingOnClearedFunds') {
                        $pendingPayment += $transaction->weekly_payment;
                    }

                    if ($transaction->payment_status !== 'Dishonoured ') {
                        $dishonoredFee += $transaction->late_fee;
                    }
                }

                // Update total amounts for various fees
                $totalWeeklyPayment += $weeklyPayment;
                $totalWeeklyInterest += $weeklyInterest;
                $totalPrincipalPayment += $principalPayment;
                $totalRescheduleFee += $rescheduleFee;
                $totalDishonoredFee += $dishonoredFee;
                $totalWeeklyEstablishmentFee += $weeklyEstablishmentFee;
                $totalCompletePayment += $completePayment;
                $totalPendingPayment += $pendingPayment;

                // Add the totals and daily transactions to the report for the current date
                $report[$formattedDate] = [
                    'transactions' => $dailyTransactions,
                    'weekly_payment' => $weeklyPayment,
                    'weekly_interest' => $weeklyInterest,
                    'principal_payment' => $principalPayment,
                    'reschedule_fee' => $rescheduleFee,
                    'dishonored_fee' => $dishonoredFee,
                    'weekly_establishment_fee' => $weeklyEstablishmentFee,
                    'completePayment' => $completePayment,
                    'pendingPayment' => $pendingPayment,
                ];
            }
            // Move to the next date
            $startDate->addDay();
        }

        $result = [
            'report' => $report,
            'totalWeeklyPayment' => $totalWeeklyPayment,
            'totalWeeklyInterest' => $totalWeeklyInterest,
            'totalPrincipalPayment' => $totalPrincipalPayment,
            'totalRescheduleFee' => $totalRescheduleFee,
            'totalDishonoredFee' => $totalDishonoredFee,
            'totalWeeklyEstablishmentFee' => $totalWeeklyEstablishmentFee,
            'totalCompletePayment' => $totalCompletePayment,
            'totalPendingPayment' => $totalPendingPayment,
        ];

        // dd($result);
        return $result;
    }


    public function generateNoOfLoansDateWise($startDate, $endDate)
    {
        // Parse start and end dates using Carbon
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        // Initialize the report array to store date-wise and overall counts
        $report = [];
        $totalLoans = 0;
        $overallCounts = [
            'active' => 0,
            'completed' => 0,
            'declined' => 0,
            'incomplete' => 0,
            'processing' => 0,
            'pending' => 0,
        ];


        // Loop through each date within the date range
        while ($startDate->lte($endDate)) {
            $formattedDate = $startDate->format('Y-m-d');

            // Get the number of loans created on the current date
            $createdLoansQuery = LoanApplication::whereDate('application_date', $formattedDate);
            $createdLoansCount = $createdLoansQuery->count();
            $totalLoans += $createdLoansCount;


            // Initialize status counts for the current date
            $statusCounts = [
                'active' => 0,
                'completed' => 0,
                'declined' => 0,
                'incomplete' => 0,
                'processing' => 0,
                'pending' => 0,
            ];

            // Get the number of loans for each status on the current date
            foreach ($statusCounts as $status => $count) {
                $query = LoanApplication::whereDate('application_date', $formattedDate)->where('status', $status)->get();
                $statusCounts[$status] = count($query);
                $overallCounts[$status] += $statusCounts[$status]; // Update overall count
            }

            // Add the counts to the report array
            $report[$formattedDate] = [
                'created_loans_count' => $createdLoansCount,
                'statuswise' => $statusCounts,
            ];

            // Move to the next date
            $startDate->addDay();
        }

        // Add overall counts to the report
        $report['overall_counts'] = $overallCounts;
        $report['totalLoans'] = $totalLoans;
        return $report;
    }

    public function generateDateWiseDirectDebitReport($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $fullReport = [];
        $grandTotalAmount = [
            'total' => 0,
            'success' => 0,
            'dishonoured' => 0,
            'pending' => 0,
            'count' => 0,
            'transactionCharge' => 0,
            'percentage' => 0,
        ];

        while ($startDate->lte($endDate)) {
            $formattedDate = $startDate->toDateString();

            $transactions = Transaction::whereDate('updated_at', $formattedDate)
                ->where('account_number', '<>', '802985425628199')
                ->where('type', 'Credit')
                ->get();

            $transactionData = [];
            $totalAmount = [
                'total' => 0,
                'success' => 0,
                'dishonoured' => 0,
                'pending' => 0,
                'count' => 0,
                'transactionCharge' => 0,
                'percentage' => 0,
            ];

            foreach ($transactions as $transaction) {
                $transactionData[] = $transaction;

                switch ($transaction->status) {
                    case 'Complete':
                        $totalAmount['success'] += $transaction->amount;
                        break;
                    case 'Dishonoured':
                        $totalAmount['dishonoured'] += $transaction->amount;
                        break;
                    case 'WaitingOnClearedFunds':
                        $totalAmount['pending'] += $transaction->amount;
                        break;
                }
            }

            $totalAmount['total'] = $transactions->sum('amount');
            $totalAmount['transactionCharge'] = $transactions->sum('fee_amount_including_gst');
            $totalAmount['count'] = $transactions->count();

            if ($totalAmount['success'] > 0) {
                $totalAmount['percentage'] = $totalAmount['success'] / $totalAmount['total'] * 100;
            }

            $report[$formattedDate] = [
                'transactions' => $transactionData,
                'total' => $totalAmount,
            ];

            $fullReport[$formattedDate] = [
                'transactions' => $transactionData,
                'total' => $totalAmount,
            ];

            $grandTotalAmount['total'] += $totalAmount['total'];
            $grandTotalAmount['success'] += $totalAmount['success'];
            $grandTotalAmount['dishonoured'] += $totalAmount['dishonoured'];
            $grandTotalAmount['pending'] += $totalAmount['pending'];
            $grandTotalAmount['count'] += $totalAmount['count'];
            $grandTotalAmount['transactionCharge'] += $totalAmount['transactionCharge'];

            $startDate->addDay();
        }

        if ($grandTotalAmount['success'] > 0) {
            $grandTotalAmount['percentage'] = $grandTotalAmount['success'] / $grandTotalAmount['total'] * 100;
        }

        return [
            'report' => $fullReport,
            'grandTotalAmount' => $grandTotalAmount,
        ];
    }

}