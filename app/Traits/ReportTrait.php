<?php



namespace App\Traits;



use App\Models\LoanApplication;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;



trait ReportTrait
{

    public function formateNumber($number)
    {

        $formattedNumber = number_format($number, 2, '.', ',');

        $formattedNumber = rtrim(rtrim($formattedNumber, '0'), '.');

        return $formattedNumber;

    }



    function getArrayOfDatesAccordingToDuration($durationInDays)
    {

        $dates = [];
        // Get the current date and time
        $currentDate = Carbon::now();
        // Loop through the duration and create an array of dates
        for ($i = 0; $i < $durationInDays; $i++) {
            $dates[] = $currentDate->subDay()->toDateString();
        }
        // Reverse the array to have dates in ascending order
        $dates = array_reverse($dates);

        return $dates;
    }

    public function approvedLoans($duration = null)
    {
        $query = LoanApplication::whereIn('status', ['active', 'completed']);

        if ($duration !== null) {
            $startDate = ($duration == 1) ? Carbon::today() : Carbon::now()->subDays($duration);
            $query->whereDate('approved_date', '>=', $startDate);
        }

        $approvedLoans = $query->get();

        // Initialize an array to store day-wise counts
        $dayWiseCounts = [];

        foreach ($approvedLoans as $loan) {
            $day = Carbon::parse($loan->approved_date)->toDateString();

            // Increment the count for the specific day
            $dayWiseCounts[$day] = ($dayWiseCounts[$day] ?? 0) + 1;
        }

        // Calculate the total count
        $totalCount = count($approvedLoans);

        return [
            'total' => $totalCount,
            'dateWise' => $dayWiseCounts,
        ];
    }



    public function getTotalPrincipalValue($duration = null)
    {
        $query = LoanApplication::whereIn('status', ['active', 'completed']);
        if ($duration) {
            $startDate = ($duration == 1) ? Carbon::today() : Carbon::now()->subDays($duration);
            $query->whereDate('approved_date', '>=', $startDate);
        }
        $loanApplications = $query->get();
        // Initialize an array to store date-wise principal values
        $dateWisePrincipalValues = [];
        foreach ($loanApplications as $loan) {
            $day = Carbon::parse($loan->approved_date)->toDateString();
            // Add the approved amount to the date-wise array
            $dateWisePrincipalValues[$day] = ($dateWisePrincipalValues[$day] ?? 0) + $loan->approved_amount;

        }

        // Calculate the total principal value
        $totalPrincipalValue = $loanApplications->sum('approved_amount');

        return [
            'total' => '$ ' . $this->formateNumber($totalPrincipalValue),
            'dateWise' => array_map([$this, 'formateNumber'], $dateWisePrincipalValues),
        ];
    }

    public function getTotalEstimatedInterestAndFee($duration = null)
    {

        $query = LoanApplication::with([

            'statements' => function ($query) {

                $query->whereNotIn('payment_status', ['crs']);

            }

        ]);



        if ($duration) {

            $startDate = ($duration == 1) ? Carbon::today() : Carbon::now()->subDays($duration);

            $query->whereDate('approved_date', '>=', $startDate);

        }



        $loanApplications = $query->get();



        // Initialize an array to store date-wise estimated interest and fee values

        $dateWiseEstimatedInterestAndFee = [];



        foreach ($loanApplications as $loanApplication) {

            $statements = $loanApplication->statements;



            $day = Carbon::parse($loanApplication->approved_date)->toDateString();



            // Add the estimated interest and fee to the date-wise array

            $dateWiseEstimatedInterestAndFee[$day] = ($dateWiseEstimatedInterestAndFee[$day] ?? 0) +

                $statements->sum('weekly_establishment_fee') +

                $statements->sum('weekly_interest');

        }



        // Calculate the total estimated interest and fee

        $totalEstimatedInterestAndFee = $loanApplications->sum(function ($loan) {

            return $loan->statements->sum('weekly_establishment_fee') + $loan->statements->sum('weekly_interest');

        });



        return [

            'total' => '$ ' . $this->formateNumber($totalEstimatedInterestAndFee),

            'dateWise' => array_map([$this, 'formateNumber'], $dateWiseEstimatedInterestAndFee),

        ];

    }

    public function getEarnedInterestAndFee($duration = null)
    {

        // Get loan applications based on duration
        $loanApplications = LoanApplication::whereIn('status', ['active', 'completed'])
            ->with('statement', 'rescheduledstatement')
            ->whereBetween('created_at', [now()->subDays($duration - 1)->startOfDay(), now()->endOfDay()])
            ->get();

        $totalEarnedInterestAndFee = 0;
        $dateWiseEarnedInterestAndFee = [];

        foreach ($loanApplications as $loanApplication) {
            $statements = $loanApplication->statement;
            $rescheduledStatements = $loanApplication->rescheduledstatement;

            $allStatements = $statements->merge($rescheduledStatements);

            foreach ($allStatements as $statement) {
                if ($statement->payment_status === 'Complete') {
                    $totalEarnedInterestAndFee += $statement->interest;

                    $date = $statement->created_at->format('Y-m-d');
                    if (isset ($dateWiseEarnedInterestAndFee[$date])) {
                        $dateWiseEarnedInterestAndFee[$date] += $statement->interest;
                    } else {
                        $dateWiseEarnedInterestAndFee[$date] = $statement->interest;
                    }
                }
            }
        }

        $formattedTotal = '$ ' . $this->formatNumber($totalEarnedInterestAndFee);
        $formattedDateWise = array_map([$this, 'formatNumber'], $dateWiseEarnedInterestAndFee);

        return [
            'total' => $formattedTotal,
            'dateWise' => $formattedDateWise,
        ];

    }
    private function formatNumber($number)
    {
        return number_format($number, 2);
    }

    public function getTotalArrearLoanCount($duration)
    {



        $query = LoanApplication::whereHas('statements', function ($query) {

            $query->where('payment_status', 'Dishonoured');

        });



        if ($duration) {

            $startDate = ($duration == 1) ? Carbon::today() : Carbon::now()->subDays($duration);

            $query->whereDate('approved_date', '>=', $startDate);

        }



        $loanApplications = $query->get();



        // Initialize an array to store date-wise arrear loan count

        $dateWiseArrearLoanCount = [];



        foreach ($loanApplications as $loanApplication) {

            $statements = $loanApplication->statements;



            $day = Carbon::parse($loanApplication->approved_date)->toDateString();



            // Increment the arrear loan count for the date

            $dateWiseArrearLoanCount[$day] = ($dateWiseArrearLoanCount[$day] ?? 0) + 1;

        }



        // Calculate the total arrear loan count

        $totalArrearLoanCount = $loanApplications->count();



        return [

            'total' => $totalArrearLoanCount,

            'dateWise' => $dateWiseArrearLoanCount,

        ];

    }

    public function getArrearAmount($duration)
    {

        $query = LoanApplication::whereHas('statements', function ($query) {

            $query->where('payment_status', 'Dishonoured');

        });



        if ($duration) {

            $startDate = ($duration == 1) ? Carbon::today() : Carbon::now()->subDays($duration);

            $query->whereDate('approved_date', '>=', $startDate);

        }



        $loanApplications = $query->with([

            'statements' => function ($query) {

                $query->where('payment_status', 'Dishonoured');

            }

        ])->get();



        // Initialize an array to store date-wise arrear amounts

        $dateWiseArrearAmount = [];



        foreach ($loanApplications as $loanApplication) {

            $statements = $loanApplication->statements;



            $day = Carbon::parse($loanApplication->approved_date)->toDateString();



            // Sum the arrear amounts for each week

            $arrearAmount = optional($statements)->sum('weekly_payment') ?? 0;



            // Add the arrear amount to the date-wise array

            $dateWiseArrearAmount[$day] = ($dateWiseArrearAmount[$day] ?? 0) + $arrearAmount;

        }



        // Calculate the total arrear amount

        $totalArrearAmount = array_sum($dateWiseArrearAmount);



        return [

            'total' => '$ ' . $this->formateNumber($totalArrearAmount),

            'dateWise' => array_map([$this, 'formateNumber'], $dateWiseArrearAmount),

        ];

    }

    public function getProblemPercentageOfLoanBook($duration)
    {

        $percentage = 0;

        $approvedLoan = $this->approvedLoans($duration);

        $DishonouredLoan = $this->getTotalArrearLoanCount($duration);



        $noOfApprovedLoan = $approvedLoan['total'];

        $noOfDishonouredLoan = $DishonouredLoan['total'];



        if ($noOfApprovedLoan) {

            $percentage = ($noOfDishonouredLoan / $noOfApprovedLoan) * 100;

        }



        // Get date-wise Dishonoured loan counts

        $dateWiseDishonouredLoans = $DishonouredLoan['dateWise'] ?? [];



        return [

            'total' => number_format($percentage, 2) . '%',

            'dateWise' => array_map(function ($count) use ($noOfApprovedLoan) {

                return number_format(($count / $noOfApprovedLoan) * 100, 2);

            }, $dateWiseDishonouredLoans),

        ];

    }



    public function getBadDebtsListWithOutstandingAmount($startDate, $endDate)
    {

        $writeOffLoans = LoanApplication::whereHas('statements', function ($query) {

            $query->where('payment_status', 'Dishonoured');

        })

            ->with([

                'statements' => function ($query) use ($startDate, $endDate) {

                    $query->where('payment_status', 'Dishonoured')

                        ->whereBetween('payment_date', [$startDate, $endDate]);

                }

            ]);



        $loanApplications = $writeOffLoans->get();



        // Initialize an array to store user-wise and date-wise write-off loan data with outstanding amount

        $userDateWiseWriteOffLoansWithAmount = [];



        foreach ($loanApplications as $loanApplication) {

            foreach ($loanApplication->statements as $statement) {

                $userId = $loanApplication->user_id;

                $day = Carbon::parse($statement->settlement_date)->toDateString();



                // Calculate the outstanding amount for the loan

                $outstandingAmount = $statement->weekly_payment;



                // Add the loan data with outstanding amount to the user-wise and date-wise array

                if ($outstandingAmount > 0) {

                    $userDateWiseWriteOffLoansWithAmount[$userId][$day][] = [

                        'loan_id' => $loanApplication->id,

                        'user_id' => $userId,

                        'settlement_date' => $day,

                        'outstanding_amount' => $outstandingAmount,

                        // Add more fields as needed

                    ];

                }

            }

        }

        return $userDateWiseWriteOffLoansWithAmount;

    }

}