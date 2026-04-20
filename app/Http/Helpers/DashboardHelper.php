<?php

namespace App\Http\Helpers;

use App\Models\FcmToken;
use App\Models\IllionCost;
use App\Models\LoanApplication;
use App\Models\LoanStatement;
use App\Models\Transaction;
use App\Models\Setting;
use App\Models\UserActivity;
use Carbon\Carbon;

class DashboardHelper
{
    public function formateNumber($number)
    {
        $formattedNumber = number_format($number, 2, '.', ',');
        $formattedNumber = rtrim(rtrim($formattedNumber, '0'), '.');
        return $formattedNumber;
    }
    function convertFormattedNumberToNumeric($formattedNumber)
    {
        // Remove commas and cast to float
        return (float) str_replace(',', '', $formattedNumber);
    }
    public function countLoan($status)
    {
        return ($status == 'all')
            ? LoanApplication::whereNotIn('status', ['incomplete', 'declined'])->count()
            : LoanApplication::where('status', $status)->count();
    }

    public function getTotalLoanDisbursed()
    {
        $loanApplications = LoanApplication::wherein('status', ['active', 'completed'])->with('transactions')->get();
        $totalCreditAmount = 0;

        foreach ($loanApplications as $loanApplication) {
            $creditTransactions = $loanApplication->transactions->where('type', 'Debit');
            $totalCreditAmount += $creditTransactions->sum('amount');
        }

        return $totalCreditAmount;
    }

    public function getTotalFeesOwing()
    {
        $loanApplications = LoanApplication::whereIn('status', ['active', 'completed'])
            ->with('statement', 'rescheduledstatement')
            ->get();

        $totalFeeOwing = 0;

        foreach ($loanApplications as $loanApplication) {
            $statements = $loanApplication->statement;
            $rescheduledStatements = $loanApplication->rescheduledstatement;

            $allStatements = $statements->merge($rescheduledStatements);

            // Filter statements where payment_status is not 'Complete' and sum the fees
            $totalFeeOwing += $allStatements->whereNotIn('payment_status', ['Complete', 'crs', 'Dishonoured', 'Canceled'])->sum('interest');
        }

        return $totalFeeOwing;
    }
    public function getTotalOverDueIntrestFeesOwing()
    {
        $loanApplications = LoanApplication::whereIn('status', ['active', 'completed'])
            ->with('statement', 'rescheduledstatement')
            ->get();

        $totalFeeOwing = 0;

        foreach ($loanApplications as $loanApplication) {
            $statements = $loanApplication->statement;
            $rescheduledStatements = $loanApplication->rescheduledstatement;

            $allStatements = $statements->merge($rescheduledStatements);

            // Filter statements where payment_status is not 'Complete' and sum the fees
            $totalFeeOwing += $allStatements->whereNotIn('payment_status', ['Complete', 'crs', 'Dishonoured', 'Canceled'])->sum('overdue_interest');
        }

        return $totalFeeOwing;
    }


    public function getTotalExtraFeesOwing()
    {
        $loanApplications = LoanApplication::whereIn('status', ['active', 'completed'])
            ->with('statement', 'rescheduledstatement')
            ->get();

        $totalRescheduleFee = 0;
        $totalLateFee = 0;

        foreach ($loanApplications as $loanApplication) {
            $statements = $loanApplication->statement;
            $rescheduledStatements = $loanApplication->rescheduledstatement;

            $allStatements = $statements->merge($rescheduledStatements);

            // Filter statements where payment_status is not 'Complete' and sum the fees
            $totalRescheduleFee += $allStatements->whereIn('payment_status', ['Re-scheduled', 'Scheduled'])
                ->sum('reschedule_fee');
            $totalLateFee += $allStatements->whereIn('payment_status', ['Re-scheduled', 'Scheduled'])
                ->sum('late_fee');
        }

        return $totalRescheduleFee + $totalLateFee;
    }

    function totalContractedInterestRate()
    {
        $loanApplications = LoanApplication::whereIn('status', ['active', 'completed'])
            ->with('statement', 'rescheduledstatement')
            ->get();

        $totalInterest = 0;
        $totalLoanAmount = 0;

        foreach ($loanApplications as $loanApplication) {
            $statements = $loanApplication->statement;
            $rescheduledStatements = $loanApplication->rescheduledstatement;

            $allStatements = $statements->merge($rescheduledStatements);

            $totalInterest += $allStatements->whereNotIn('payment_status', ['crs'])->sum('interest');
            $totalInterest += $allStatements->whereNotIn('payment_status', ['crs'])->sum('late_fee');
            $totalInterest += $allStatements->whereNotIn('payment_status', ['crs'])->sum('reschedule_fee');
            $totalLoanAmount += $allStatements->whereNotIn('payment_status', ['crs'])->sum('principal_payment');
        }

        // Calculate the average interest rate as a percentage

        if ($totalLoanAmount > 0) {
            $averageInterestRate = ($totalInterest / $totalLoanAmount) * 100;
            return $this->formateNumber($averageInterestRate);
        } else {
            // Handle the case where totalLoanAmount is zero to avoid division by zero
            return 0;
        }

    }

    public function revenueCollected()
    {
        //$revenueCollected = LoanStatement::where('payment_status', 'Complete')->sum('weekly_payment');
        $revenueCollected = 0;
        $revenueCollected += LoanStatement::where('payment_status', 'Complete')->sum('principal_payment');
        $revenueCollected += LoanStatement::where('payment_status', 'Complete')->sum('interest');
        $revenueCollected += LoanStatement::where('payment_status', 'Complete')->sum('late_fee');
        $revenueCollected += LoanStatement::where('payment_status', 'Complete')->sum('reschedule_fee');

        return $this->formateNumber($revenueCollected);
    }

    public function totalExpectedRevenue()
    {
        $loanApplications = LoanApplication::whereIn('status', ['active', 'completed'])
            ->with('statement', 'rescheduledstatement')
            ->get();


        $totalExpectedRevenue = 0;

        foreach ($loanApplications as $loanApplication) {
            $statements = $loanApplication->statement;
            $rescheduledStatements = $loanApplication->rescheduledstatement;

            $allStatements = $statements->merge($rescheduledStatements);


            // Filter statements where payment_status is not 'Complete' and sum the fees
            $totalExpectedRevenue += $allStatements->wherenotIn('payment_status', ['crs', 'Dishonoured', 'Canceled'])->sum('weekly_payment');


        }

        return $this->formateNumber($totalExpectedRevenue);
    }

    public function getTotalFeesCollected()
    {

        $totalFeeOwing = LoanStatement::where('payment_status', 'Complete')->sum('interest');
        return $totalFeeOwing;

    }

    public function getRevenueCollectedRate()
    {
        $expectedRevenue = $this->convertFormattedNumberToNumeric($this->totalExpectedRevenue());
        $revenueCollected = $this->convertFormattedNumberToNumeric($this->revenueCollected());

        // Check if $expectedRevenue is zero before performing division
        $rate = ($expectedRevenue != 0) ? ($revenueCollected / $expectedRevenue) * 100 : 0;

        return $this->formateNumber($rate);
    }

    public function calculateFeesCollected($includeInterest = false)
    {
        $feesCollected = 0;

        // Sum weekly establishment fee, late fee, and reschedule fee
        $feesCollected += LoanStatement::where('payment_status', 'Complete')->sum('late_fee');
        $feesCollected += LoanStatement::where('payment_status', 'Complete')->sum('reschedule_fee');


        return $feesCollected;
    }
    public function calculatePrincipleCollected($includeInterest = false)
    {
        $feesCollected = 0;

        // Sum weekly establishment fee, late fee, and reschedule fee
        $feesCollected += LoanStatement::where('payment_status', 'Complete')->sum('principal_payment');


        return $this->formateNumber($feesCollected);
    }

    public function getActiveLoansInArrearsWithDishonoredPayments()
    {
        $activeLoansInArrears = LoanApplication::whereHas('statements', function ($query) {
            $query->where('payment_status', 'Dishonoured');
        })->where('status', 'Active')

            ->count();

        return $activeLoansInArrears;
    }
    public function getRecoveringLoans()
    {
        $activeLoansInArrears = LoanApplication::whereHas('statements', function ($query) {
            $query->where('payment_status', 'Dishonoured');
        })->where('status', 'Active')->get();

        $recoveringLoans = [];

        foreach ($activeLoansInArrears as $application) {

            $latestPaidStatement = LoanStatement::where('loan_application_id', $application->id)->where('payment_status', 'Complete')->latest('settlement_date')->first();
            $latestDishonouredStatement = LoanStatement::where('loan_application_id', $application->id)->where('payment_status', 'Dishonoured')->latest('settlement_date')->first();
            // dd($latestDishonouredStatement);


            // Check if both latest paid and dishonoured statements exist
            if ($latestPaidStatement && $latestDishonouredStatement) {
                // Compare timestamps
                if ($latestPaidStatement->settlement_date < $latestDishonouredStatement->settlement_date) {
                    // Exclude from the list of recovering loans
                    continue;
                } else {
                    $recoveringLoans[] = $application;
                }
            }

            // If control reaches here, it means the loan is still recovering
        }

        return $recoveringLoans;
    }

    public function getActiveLoansWithCompletePaymentStatements()
    {
        $activeLoans = LoanApplication::whereDoesntHave('statements', function ($query) {
            $query->where('payment_status', 'Dishonoured');
        })
            ->whereHas('statements', function ($query) {
                $query->where('payment_status', 'Complete');
            })->where('status', 'Active')
            ->count();

        return $activeLoans;
    }

    public function getDishonoredPercentage()
    {
        $totalActiveLoans = $this->getActiveLoansWithCompletePaymentStatements();
        $dishonoredLoans = $this->getActiveLoansInArrearsWithDishonoredPayments();

        if ($totalActiveLoans == 0) {
            return 0; // Avoid division by zero
        }

        $dishonoredPercentage = ($totalActiveLoans / $dishonoredLoans) * 100;

        return $this->formateNumber($dishonoredPercentage);
    }

    public function getArrearAmount()
    {
        $arrearAmount = LoanApplication::whereHas('statements', function ($query) {
            $query->where('payment_status', 'Dishonoured');
        })
            ->with([
                'statements' => function ($query) {
                    $query->where('payment_status', 'Dishonoured');
                }
            ])
            ->get()
            ->sum(function ($loan) {
                // Check if statements relationship exists before summing weekly_payment
                return optional($loan->statements)->sum('weekly_payment') ?? 0;
            });
        return $this->formateNumber($arrearAmount);

    }
    public function getArrearAmountCollected()
    {
        $arrearAmmountCollected = LoanStatement::where('parent_id', '!=', 'null')
            ->where('late_fee', '>', '0')
            ->where('payment_status', 'Complete')
            ->get();

        $totalCollected = 0;
        foreach ($arrearAmmountCollected as $a) {
            $totalCollected += $a->principal_payment + $a->interest;
        }

        return $this->formateNumber($totalCollected);

    }
    public function getArrearOwing()
    {
        $getArrearAmount = $this->convertFormattedNumberToNumeric($this->getArrearAmount());
        $getArrearAmountCollected = $this->convertFormattedNumberToNumeric($this->getArrearAmountCollected());

        // Check if $expectedRevenue is zero before performing division
        $total = $getArrearAmount - $getArrearAmountCollected;

        return $this->formateNumber($total);

    }
    public function getArrearOwingRate()
    {
        $getArrearAmount = $this->convertFormattedNumberToNumeric($this->getArrearAmount());
        $getArrearAmountCollected = $this->convertFormattedNumberToNumeric($this->getArrearAmountCollected());

        // Check if $expectedRevenue is zero before performing division
        $rate = ($getArrearAmount != 0) ? ($getArrearAmountCollected / $getArrearAmount) * 100 : 0;

        return $this->formateNumber($rate);

    }

    function removeSlug($string)
    {
        // Replace underscores with an empty string
        return str_replace('_', ' ', $string);
    }

    function getLatestTransaction()
    {
        $transactions = Transaction::orderBy('id', 'desc')->limit(5)->get();
        return $transactions;
    }

    function getLatestActivities()
    {
        $activity = UserActivity::orderBy('id', 'desc')->limit(5)->get();
        return $activity;
    }

    function getDownloads($platform)
    {
        $device = FcmToken::where('platform', $platform)->count();
        return $device;
    }
    function getDownloadsThisMonth($platform)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $downloads = FcmToken::where('platform', $platform)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        return $downloads;
    }

    function getTodayRepayments()
    {
        $transaction = LoanStatement::where('settlement_date', date("Y-m-d"))
            ->whereNotIn('payment_status', ['Dishourned', 'crs'])
            ->get();

        return $transaction;
    }

    function getTodayRepaymentsTotal()
    {
        $transactions = LoanStatement::where('settlement_date', date("Y-m-d"))
            ->whereNotIn('payment_status', ['Dishourned', 'crs'])
            ->get();

        $totalAmount = $transactions->sum('weekly_payment');

        return $totalAmount;
    }

    function getTomorrowRepaymentsTotal()
    {
        $tomorrowDate = date("Y-m-d", strtotime("+1 day"));

        $transactions = LoanStatement::where('settlement_date', $tomorrowDate)
            ->whereNotIn('payment_status', ['Dishourned', 'crs'])
            ->get();

        $totalAmount = $transactions->sum('weekly_payment');

        return $totalAmount;
    }
    function getTomorrowRepayments()
    {
        $tomorrowDate = date("Y-m-d", strtotime("+1 day"));

        $transactions = LoanStatement::where('settlement_date', $tomorrowDate)
            ->whereNotIn('payment_status', ['Dishourned', 'crs'])
            ->get();



        return $transactions;
    }

    function getRepaymentsByDate($date)
    {
        $transaction = LoanStatement::where('settlement_date', $date)
            ->whereNotIn('payment_status', ['Dishourned', 'crs'])
            ->get();

        return $transaction;
    }

    function getRepaymentsByDateTotal($date)
    {
        $transactions = LoanStatement::where('settlement_date', $date)
            ->whereNotIn('payment_status', ['Dishourned', 'crs'])
            ->get();

        $totalAmount = $transactions->sum('weekly_payment');

        return $totalAmount;
    }

    function getTransactionCompletedByDateandType($date, $type)
    {

        $formattedDate = date('Y-m-d', strtotime($date));


        $transactions = Transaction::whereDate('updated_at', $formattedDate)
            ->where('status', 'Complete')
            ->where('type', $type)
            ->get();
        ;

        $totalAmountToday = $transactions->sum('amount');

        $lendingtransactions = Transaction::whereDate('updated_at', $formattedDate)
            ->where('account_number', '802985425628199')
            ->where('type', $type)
            ->get();
        ;

        $lendingtransactionsToday = $lendingtransactions->sum('amount');

        $totalAmountToday = $totalAmountToday - $lendingtransactionsToday;

        $totalAmountYesterday = $transactions->where('updated_at', date('Y-m-d', strtotime('-1 day', strtotime($formattedDate))))->sum('amount');

        $difference = $totalAmountToday - $totalAmountYesterday;
        $percentageDifference = ($difference / ($totalAmountYesterday != 0 ? $totalAmountYesterday : 1)) * 100;


        return [
            'totalAmountToday' => $totalAmountToday,
            'totalAmountYesterday' => $totalAmountYesterday,
            'difference' => $percentageDifference
        ];
    }
    function getTransactionPendingByDateandType($date)
    {

        $formattedDate = date('Y-m-d', strtotime($date));


        $transactions = Transaction::whereDate('created_at', $formattedDate)
            ->where('status', 'WaitingOnClearedFunds')
            ->get();


        $totalAmountToday = $transactions->sum('amount');

        $totalAmountYesterday = $transactions->where('created_at', date('Y-m-d', strtotime('-1 day', strtotime($formattedDate))))->sum('amount');

        $difference = $totalAmountToday - $totalAmountYesterday;
        $percentageDifference = ($difference / ($totalAmountYesterday != 0 ? $totalAmountYesterday : 1)) * 100;


        return [
            'totalAmountToday' => $totalAmountToday,
            'totalAmountYesterday' => $totalAmountYesterday,
            'difference' => $percentageDifference
        ];
    }
    function getTransactionDishonouredByDateandType($date)
    {

        $formattedDate = date('Y-m-d', strtotime($date));


        $transactions = Transaction::whereDate('updated_at', $formattedDate)
            ->where('status', 'Dishonoured')
            ->get();
        ;

        $totalAmountToday = $transactions->sum('amount');

        $totalAmountYesterday = $transactions->where('created_at', date('Y-m-d', strtotime('-1 day', strtotime($formattedDate))))->sum('amount');

        $difference = $totalAmountToday - $totalAmountYesterday;
        $percentageDifference = ($difference / ($totalAmountYesterday != 0 ? $totalAmountYesterday : 1)) * 100;


        return [
            'totalAmountToday' => $totalAmountToday,
            'totalAmountYesterday' => $totalAmountYesterday,
            'difference' => $percentageDifference
        ];
    }
    function getExpectedClearneceofFund($date)
    {

        $formattedDate = date('Y-m-d', strtotime($date));


        $transactions = Transaction::whereDate('expected_clearance_date_for_funds', $formattedDate)
            ->get();
        ;

        $totalAmountToday = $transactions->sum('amount');

        $totalAmountYesterday = Transaction::whereDate('expected_clearance_date_for_funds', date('Y-m-d', strtotime('+1 day', strtotime($formattedDate))))->sum('amount');

        $difference = $totalAmountToday - $totalAmountYesterday;
        $percentageDifference = ($difference / ($totalAmountYesterday != 0 ? $totalAmountYesterday : 1)) * 100;


        return [
            'totalAmountToday' => $totalAmountToday,
            'totalAmountYesterday' => $totalAmountYesterday,
            'difference' => $percentageDifference
        ];
    }

    public function generateDishonoredStatementReport()
    {
        // Get dishonored statements grouped by loan_application_id
        $dishonoredStatements = LoanStatement::where('payment_status', 'Dishonoured')
            ->join('loan_applications', 'loan_statements.loan_application_id', '=', 'loan_applications.id')
            ->where('loan_applications.status', 'active') // Assuming 'active' column indicates the active status
            ->select('loan_statements.loan_application_id', \DB::raw('COUNT(*) as dishonored_count'))
            ->groupBy('loan_statements.loan_application_id')
            ->get();

        // Initialize an array to store the grouped statements
        $groupedStatements = [];

        // Group the statements by the number of dishonored statements
        foreach ($dishonoredStatements as $statement) {
            $dishonoredCount = $statement->dishonored_count;

            if (!isset($groupedStatements[$dishonoredCount])) {
                $groupedStatements[$dishonoredCount] = [];
            }

            $groupedStatements[$dishonoredCount][] = $statement;
        }

        ksort($groupedStatements);

        return $groupedStatements;
    }
    public function generateReturningCustomerReport()
    {
        // Check if the database connection is available
        if (!\DB::connection()) {
            // Handle error: Database connection is not available
            return "Database connection not available";
        }

        try {
            $returningCustomers = LoanApplication::select('user_id', \DB::raw('COUNT(*) as loan_count'))
                ->whereNotIn('status', ['declined'])
                ->groupBy('user_id')
                ->get();

            $returningCustomerCounts = [];

            foreach ($returningCustomers as $customer) {
                $loanCount = $customer->loan_count;
                if ($loanCount > 1) {
                    if (!isset($returningCustomerCounts[$loanCount])) {
                        $returningCustomerCounts[$loanCount] = [];
                    }
                    $returningCustomerCounts[$loanCount][] = $customer;
                }
            }

            ksort($returningCustomerCounts);

            return $returningCustomerCounts;
        } catch (\Exception $e) {
            // Handle error: Exception occurred
            return "An error occurred: " . $e->getMessage();
        }
    }

    public function generateReturningCustomerReports()
    {
        // Count the number of loans each user has applied for
        $returningCustomers = LoanApplication::select('user_id', \DB::raw('COUNT(*) as loan_count'))
            ->groupBy('user_id')
            ->get();

        // Initialize an array to store the count of returning customers for each loan count
        $returningCustomerCounts = [];

        // Iterate through the results and count the returning customers for each loan count
        foreach ($returningCustomers as $customer) {
            $loanCount = $customer->loan_count;
            if (!isset($returningCustomerCounts[$loanCount])) {
                $returningCustomerCounts[$loanCount] = 0;
            }
            $returningCustomerCounts[$loanCount]++;
        }

        // Calculate the total number of customers
        $totalCustomers = count($returningCustomers);

        // Initialize an array to store the percentage of returning customers for each loan count
        $percentageReturningCustomers = [];

        // Calculate the percentage of returning customers for each loan count
        foreach ($returningCustomerCounts as $loanCount => $count) {
            $percentage = ($count / $totalCustomers) * 100;
            $percentageReturningCustomers[$loanCount] = $percentage;
        }

        // Now $percentageReturningCustomers contains the percentage of returning customers for each loan count

        // Example usage:
        foreach ($percentageReturningCustomers as $loanCount => $percentage) {
            echo "Percentage of customers returning for $loanCount loans: $percentage%\n";
        }
    }

    public function getExcessiveOutstandingAccount()
    {
        // Retrieve loan applications with excessive outstanding amounts
        $excessiveAccounts = LoanApplication::where('excessive_outstanding_flag', true)->get();

        return $excessiveAccounts;
    }
    public function getHighRiskAccount()
    {
        // Retrieve loan applications with excessive outstanding amounts
        $excessiveAccounts = LoanApplication::where('high_risk_customer', true)->get();

        return $excessiveAccounts;
    }
    public function getTotalLending()
    {
        // Retrieve loan applications with excessive outstanding amounts
        $getTotalLending = Transaction::where('account_number', '802985425628199')->sum('amount');

        return $getTotalLending;
    }

    function calculateMatchPercentage($string1, $string2)
    {
        $string1 = strtolower(trim($string1));
        $string2 = strtolower(trim($string2));

        $levenshteinDistance = levenshtein($string1, $string2);

        // Calculate the maximum possible distance
        $maxLen = max(strlen($string1), strlen($string2));

        // Avoid division by zero
        if ($maxLen === 0) {
            return 100;
        }

        // Calculate the similarity percentage
        $similarityPercentage = ((1 - ($levenshteinDistance / $maxLen)) * 100);

        return number_format($similarityPercentage, 2);
    }

    public static function calculateCosts()
    {
        $currentDate = Carbon::now();
        $currentMonth = $currentDate->month;
        $currentYear = $currentDate->year;

        $lastMonthDate = $currentDate->copy()->subMonth();
        $lastMonth = $lastMonthDate->month;
        $lastYear = $lastMonthDate->year;

        $yesterdayDate = $currentDate->copy()->subDay();

        // Lifetime cost
        $lifetimeCost = IllionCost::sum('amount');

        // Current month cost
        $currentMonthCost = IllionCost::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('amount');

        // Last month cost
        $lastMonthCost = IllionCost::whereMonth('created_at', $lastMonth)
            ->whereYear('created_at', $lastYear)
            ->sum('amount');

        // Today's cost
        $todayCost = IllionCost::whereDate('created_at', $currentDate->toDateString())
            ->sum('amount');

        // Yesterday's cost
        $yesterdayCost = IllionCost::whereDate('created_at', $yesterdayDate->toDateString())
            ->sum('amount');

        return [
            'lifetime' => $lifetimeCost,
            'current_month' => $currentMonthCost,
            'last_month' => $lastMonthCost,
            'today' => $todayCost,
            'yesterday' => $yesterdayCost,
        ];
    }
}
