<?php

namespace App\Traits;

use App\Models\LoanApplication;
use App\Models\LoanStatement;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait CohortReportTrait
{
    function generateCohortReport($date_from, $date_to, $start_from, $number_of_loans)
    {
        $total_number_of_loans = $this->getTotalNumberOfLoans($date_from, $date_to);

        $numberCohortBox = ceil($total_number_of_loans / $number_of_loans);

        $cohorts = [];

        $cashPriceOfTotalLoansTotal = 0;
        $cashPriceOfTotalActiveLoansTotal = 0;
        $totalNumberOfClientsTotal = 0;
        $numberOfActiveClientsTotal = 0;
        $cashPriceValueOfSureshotBadDebtClientsTotal = 0;
        $accruedBookValueOfSureshotBadBebtClientsTotal = 0;
        $numberOfSureshotBadDebtClientsTotal = 0;
        $firstPaymentDishonoredTotal = 0;
        $percentageOfSureshotBadDebtInNumberTotal = 0;
        $percentageOfSureshotBadDebtInCashPriceTotal = 0;
        $percentageOfDishonoredTotal = 0;
        $averageCollectionRateTotal = 0;

        $cohortNumber = $start_from;

        // Generate the cohort labels and count active clients for each cohort
        for ($i = 1; $i <= $numberCohortBox; $i++) {
            $offset = ($i - 1) * $number_of_loans;

            $cashPriceOfTotalLoans = $this->getCashPriceOfTotalLoans($date_from, $date_to, $offset, $number_of_loans);
            $cashPriceOfTotalActiveLoans = $this->getCashPriceOfTotalActiveLoans($date_from, $date_to, $offset, $number_of_loans);
            $totalNumberOfClients = $this->getNumberOfClients($date_from, $date_to, $offset, $number_of_loans);
            $numberOfActiveClients = $this->getNumberOfActiveClients($date_from, $date_to, $offset, $number_of_loans);
            $cashPriceValueOfSureshotBadDebtClients = $this->geCashPriceValueOfSureShotBadDebtClients($date_from, $date_to, $offset, $number_of_loans);
            $accruedBookValueOfSureshotBadBebtClients = $this->getAccuredBookValueOfSureShotBadDebtClients($date_from, $date_to, $offset, $number_of_loans);
            $numberOfSureshotBadDebtClients = $this->getNumberOfSureshotBadDebts($date_from, $date_to, $offset, $number_of_loans);
            $averageCollectionRate = $this->getCollectionAmount($date_from, $date_to, $offset, $number_of_loans);




            $firstPaymentDishonored = number_format($this->getFirstPaymentDishonored($date_from, $date_to, $offset, $number_of_loans) / $totalNumberOfClients * 100, 2);
            $percentageOfSureshotBadDebtInNumber = number_format($numberOfSureshotBadDebtClients / $totalNumberOfClients * 100, 2);
            $percentageOfSureshotBadDebtInCashPrice = number_format($cashPriceValueOfSureshotBadDebtClients / $cashPriceOfTotalLoans * 100, 2);
            $percentageOfDishonored = number_format($this->getDishournedPercentage($date_from, $date_to, $offset, $number_of_loans / $totalNumberOfClients * 100, 2));


            //Total of all factor
            $cashPriceOfTotalLoansTotal += $cashPriceOfTotalLoans;
            $cashPriceOfTotalActiveLoansTotal += $cashPriceOfTotalActiveLoans;
            $totalNumberOfClientsTotal += $totalNumberOfClients;
            $numberOfActiveClientsTotal += $numberOfActiveClients;
            $cashPriceValueOfSureshotBadDebtClientsTotal += $cashPriceValueOfSureshotBadDebtClients;
            $accruedBookValueOfSureshotBadBebtClientsTotal += $accruedBookValueOfSureshotBadBebtClients;
            $numberOfSureshotBadDebtClientsTotal += $numberOfSureshotBadDebtClients;
            $firstPaymentDishonoredTotal += $firstPaymentDishonored;
            $percentageOfSureshotBadDebtInNumberTotal += $percentageOfSureshotBadDebtInNumber;
            $percentageOfSureshotBadDebtInCashPriceTotal += $percentageOfSureshotBadDebtInCashPrice;
            $percentageOfDishonoredTotal += $percentageOfDishonored;
            $averageCollectionRateTotal += $averageCollectionRate;


            $cohorts["Cohort " . $cohortNumber] = [
                'cash_price_of_total_loans' => [
                    'value' => $cashPriceOfTotalLoans,
                    'type' => 'money',
                ],
                'cash_price_of_total_active_loans' => [
                    'value' => $cashPriceOfTotalActiveLoans,
                    'type' => 'money',
                ],
                'total_client_of_that_cohort' => [
                    'value' => $totalNumberOfClients,
                    'type' => 'number',
                ],
                'number_of_active_clients' => [
                    'value' => $numberOfActiveClients,
                    'type' => 'number',
                ],
                'number_of_sureshot_bad_debts_clients' => [
                    'value' => $numberOfSureshotBadDebtClients,
                    'type' => 'number',
                ],
                'accrued_book_value_of_sureshot_bad_debt_clients' => [
                    'value' => $accruedBookValueOfSureshotBadBebtClients,
                    'type' => 'money',
                ],
                'cash_price_value_of_sureshot_bad_debt_clients' => [
                    'value' => $cashPriceValueOfSureshotBadDebtClients,
                    'type' => 'money',
                ],
                'average_collection_rate' => [
                    'value' => $averageCollectionRate,
                    'type' => 'percentage',
                ],
                'percentage_of_sureshot_bad_debt_in_number' => [
                    'value' => $percentageOfSureshotBadDebtInNumber,
                    'type' => 'percentage',
                ],
                'percentage_of_sureshot_bad_debt_in_cash_price' => [
                    'value' => $percentageOfSureshotBadDebtInCashPrice,
                    'type' => 'percentage',
                ],
                'first_payment_dishonored' => [
                    'value' => $firstPaymentDishonored,
                    'type' => 'percentage',
                ],
                'percentage_of_dishonored_payments' => [
                    'value' => $percentageOfDishonored,
                    'type' => 'percentage',
                ],
            ];

            $cohortNumber = $cohortNumber + $number_of_loans;

        }

        $cohorts['Average'] = [
            'cash_price_of_total_loans' => [
                'value' => $cashPriceOfTotalLoansTotal / $numberCohortBox,
                'type' => 'money',
            ],
            'cash_price_of_total_active_loans' => [
                'value' => $cashPriceOfTotalActiveLoansTotal / $numberCohortBox,
                'type' => 'money',
            ],
            'total_client_of_that_cohort' => [
                'value' => number_format($totalNumberOfClientsTotal / $numberCohortBox,2),
                'type' => 'number',
            ],
            'number_of_active_clients' => [
                'value' => number_format($numberOfActiveClientsTotal / $numberCohortBox,2),
                'type' => 'number',
            ],
            'number_of_sureshot_bad_debts_clients' => [
                'value' =>number_format($numberOfSureshotBadDebtClientsTotal / $numberCohortBox,2),
                'type' => 'number',
            ],
            'accrued_book_value_of_sureshot_bad_debt_clients' => [
                'value' => $accruedBookValueOfSureshotBadBebtClientsTotal / $numberCohortBox,
                'type' => 'money',
            ],
            'cash_price_value_of_sureshot_bad_debt_clients' => [
                'value' => $cashPriceValueOfSureshotBadDebtClientsTotal / $numberCohortBox,
                'type' => 'money',
            ],
            'average_collection_rate' => [
                'value' => number_format($averageCollectionRateTotal / $numberCohortBox,2) ,
                'type' => 'percentage',
            ],
            'percentage_of_sureshot_bad_debt_in_number' => [
                'value' => number_format($percentageOfSureshotBadDebtInNumberTotal / $numberCohortBox, 2),
                'type' => 'percentage',
            ],
            'percentage_of_sureshot_bad_debt_in_cash_price' => [
                'value' => number_format($percentageOfSureshotBadDebtInCashPriceTotal / $numberCohortBox, 2),
                'type' => 'percentage',
            ],
            'first_payment_dishonored' => [
                'value' => number_format($firstPaymentDishonoredTotal / $numberCohortBox, 2),
                'type' => 'percentage',
            ],
            'percentage_of_dishonored_payments' => [
                'value' => number_format($percentageOfDishonoredTotal / $numberCohortBox, 2),
                'type' => 'percentage',
            ],
        ];

       
        return $cohorts;
    }

    function getTotalNumberOfLoans($date_from, $date_to)
    {
        return LoanApplication::whereBetween('approved_date', [$date_from, $date_to])
            ->whereIn('status', ['active', 'completed'])
            ->orderBy('approved_date')
            ->count();
    }

    function getNumberOfActiveClients($date_from, $date_to, $offset, $limit)
    {
        $activeLoans = LoanApplication::whereBetween('approved_date', [$date_from, $date_to])
            ->whereIn('status', ['active', 'completed'])
            ->orderBy('approved_date')
            ->skip($offset)
            ->take($limit)
            ->get();

        return $activeLoans->where('status', 'active')->count();
    }
    function getNumberOfClients($date_from, $date_to, $offset, $limit)
    {
        $activeLoans = LoanApplication::whereBetween('approved_date', [$date_from, $date_to])
            ->whereIn('status', ['active', 'completed'])
            ->orderBy('approved_date')
            ->skip($offset)
            ->take($limit)
            ->get();

        return (count($activeLoans));
    }

    function getCashPriceOfTotalLoans($date_from, $date_to, $offset, $limit)
    {
        $activeLoans = LoanApplication::whereBetween('approved_date', [$date_from, $date_to])
            ->whereIn('status', ['active', 'completed'])
            ->orderBy('approved_date')
            ->skip($offset)
            ->take($limit)
            ->get();

        return $activeLoans->sum('approved_amount');
    }
    function getCashPriceOfTotalActiveLoans($date_from, $date_to, $offset, $limit)
    {
        $activeLoans = LoanApplication::whereBetween('approved_date', [$date_from, $date_to])
            ->whereIn('status', ['active', 'completed'])
            ->orderBy('approved_date')
            ->skip($offset)
            ->take($limit)
            ->get();

        return $activeLoans->where('status', 'active')->sum('approved_amount');
    }
    function getNumberOfSureshotBadDebts($date_from, $date_to, $offset, $limit)
    {
        $activeLoans = LoanApplication::whereBetween('approved_date', [$date_from, $date_to])
            ->whereIn('status', ['active', 'completed'])
            ->orderBy('approved_date')
            ->skip($offset)
            ->take($limit)
            ->get();

        return $activeLoans->where('status', 'active')->where('is_bad_debt', 1)->count();
    }
    function getAccuredBookValueOfSureShotBadDebtClients($date_from, $date_to, $offset, $limit)
    {
        $activeLoans = LoanApplication::whereBetween('approved_date', [$date_from, $date_to])
            ->whereIn('status', ['active', 'completed'])
            ->orderBy('approved_date')
            ->skip($offset)
            ->take($limit)
            ->get();

        $total_amount = 0;
        foreach ($activeLoans as $applications) {
            if ($applications->is_bad_debt == 1) {
                $total_amount += $applications->totalLoanOutstanding->sum('weekly_payment');
            }

        }

        return $total_amount;

    }
    function geCashPriceValueOfSureShotBadDebtClients($date_from, $date_to, $offset, $limit)
    {
        $activeLoans = LoanApplication::whereBetween('approved_date', [$date_from, $date_to])
            ->whereIn('status', ['active', 'completed'])
            ->orderBy('approved_date')
            ->skip($offset)
            ->take($limit)
            ->get();

        $total_amount = 0;
        foreach ($activeLoans as $applications) {
            if ($applications->is_bad_debt == 1) {
                $total_amount += $applications->approved_amount;
            }

        }

        return $total_amount;

    }
    function getFirstPaymentDishonored($date_from, $date_to, $offset, $limit)
    {
        $activeLoans = LoanApplication::whereBetween('approved_date', [$date_from, $date_to])
            ->whereIn('status', ['active', 'completed'])
            ->orderBy('approved_date')
            ->skip($offset)
            ->take($limit)
            ->get();

        $totalNumberofFirstPaymentDishourned = 0;
        foreach ($activeLoans as $applications) {
            $firstStatement = $applications->getFirstStatement;
            if ($firstStatement) {
                if ($firstStatement->payment_status == 'Dishonoured') {
                    $totalNumberofFirstPaymentDishourned++;
                }
            }
        }
        return $totalNumberofFirstPaymentDishourned;
    }
    function getDishournedPercentage($date_from, $date_to, $offset, $limit)
    {
        $activeLoans = LoanApplication::whereBetween('approved_date', [$date_from, $date_to])
            ->whereIn('status', ['active', 'completed'])
            ->orderBy('approved_date')
            ->skip($offset)
            ->take($limit)
            ->get();

        $totalNumberofPaymentDishourned = 0;
        foreach ($activeLoans as $applications) {
            $dishonouredStatement = $applications->getLatestDishonouredStatement;
            if ($dishonouredStatement) {
                $totalNumberofPaymentDishourned++;
            }
        }
        return $totalNumberofPaymentDishourned;
    }
    function getCollectionAmount($date_from, $date_to, $offset, $limit)
    {
        $activeLoans = LoanApplication::whereBetween('approved_date', [$date_from, $date_to])
            ->whereIn('status', ['active', 'completed'])
            ->orderBy('approved_date')
            ->skip($offset)
            ->take($limit)
            ->get();

            $totalCompletedStatement = 0;
            $totalLoanAmount = 0;
        
        foreach ($activeLoans as $applications) {
           
           $completedStatement = $applications->completestatementDateRange($date_from, $date_to)->sum('weekly_payment');
           $loanAmount = $applications->totalLoanAmountDateWise($date_from, $date_to)->sum('weekly_payment');
           $totalCompletedStatement += $completedStatement;
           $totalLoanAmount += $loanAmount;
        }

        $averageCollectionRate = ($totalLoanAmount != 0) ? ($totalCompletedStatement / $totalLoanAmount) * 100 : 0;

        return number_format($averageCollectionRate,2);
      
    }
}
