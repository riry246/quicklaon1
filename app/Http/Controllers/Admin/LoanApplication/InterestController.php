<?php

namespace App\Http\Controllers\Admin\LoanApplication;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\LoanStatement;
use App\Traits\LoanTrait;
use App\Traits\MessageTrait;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InterestController extends Controller
{

    use MessageTrait, LoanTrait;

    public function index()
    {
        try {
            $excludedIds = [696, 711, 423, 267, 177, 2037, 1784, 201, 2148, 544, 304, 918, 954, 739, 719, 195, 56, 1943, 1476, 2797, 1181, 3631];
            $loanApplications = LoanApplication::where('status', 'active')->get();


            foreach ($loanApplications as $application) {

                $application_end_date = $this->checkApplicationEndDate($application);

                $weeklyRates = $this->getInterest($application);
                $weeklyInterestRate = $weeklyRates['interest'];
                $weeklyEstablishmentFeeRate = $weeklyRates['establishmentFee'];

                $overdue_repayments = $this->checkOverDuePayments($application);

                foreach ($overdue_repayments as $s) {
                    $s->weekly_interest = $weeklyInterestRate;
                    $s->weekly_establishment_fee = $weeklyEstablishmentFeeRate;

                    if (!$s->overdue_interest) {
                        $s->overdue_interest = $weeklyInterestRate;
                        $s->interest += $weeklyInterestRate;
                        if (!in_array($application->id, $excludedIds)) {
                            $s->weekly_payment += $weeklyInterestRate;
                        }
                    }

                    $s->save();
                }




            }
            return redirect()->back()->with('success', 'Interest of 4% has been successfully applied to statements beyond the loan maturity date.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }

    }

    public function correctInterest()
    {
        try {
            $loanApplications = LoanApplication::whereIn('status', ['active','completed'])->get();


            foreach ($loanApplications as $application) {

                $application_end_date = $this->checkApplicationEndDate($application);

                $weeklyRates = $this->getInterest($application);
                $weeklyInterestRate = $weeklyRates['interest'];
                $weeklyEstablishmentFeeRate = $weeklyRates['establishmentFee'];

                $statement = $statement = LoanStatement::where('loan_application_id', $application->id)->get();

                foreach ($statement as $s) {
                    $s->weekly_interest = $weeklyInterestRate;
                    $s->weekly_establishment_fee = $weeklyEstablishmentFeeRate;
                    $s->save();
                }




            }
            return redirect()->back()->with('success', 'Interest correction has been successfully completed.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }

    }



}
