<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\GeneralTrait;
use App\Traits\MonoovaTrait;
use App\Traits\ReportTrait;
use Illuminate\Http\Request;
use Auth;

class DashboardController extends Controller
{
    use ReportTrait, GeneralTrait, MonoovaTrait;

    private $module_name = 'Dashboard';
    private $title = 'Dashboard';
    private $module = 'dashboard';
    private $url = 'home';

    public function index()
    {
        //$data['totalLending'] = $this->totalLending(7);
        //$data['badDebt'] = $this->getBadDebtsListWithOutstandingAmount('2023-12-03','2023-12-20');

        // dd($data);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Dashboard', $this->url, null);
        $data['time'] = true;

      //  $data['moonova'] = $this->accountDetail();


        return view('admin.dashboard', $data);
    }



    public function getMetrics(Request $request)
    {

        $duration = $request->duration;
        $url = $request->url;

        $data['metrics'] = $this->$url($duration);

        return view('admin.dashboard.metrics', $data);
    }
    public function showBadDebt(Request $request)
    {

        $formattedFromDate = $request->formattedFromDate;
        $formattedToDate = $request->formattedToDate;


        $data['badDebt'] = $this->getBadDebtsListWithOutstandingAmount($formattedFromDate, $formattedToDate);
        //dd($data);
        $data['datefrom'] = $formattedFromDate;
        $data['dateto'] = $formattedToDate;

        return view('admin.dashboard.widgets.badDebt', $data);
    }
    public function totalLending($duration)
    {

        $data = array(
            'metrics' => array(
                'total_loans' => $this->approvedLoans($duration),
                'total_principle_value_($)' => $this->getTotalPrincipalValue($duration),
                'total_estimated_interest_and_fee_($)' => $this->getTotalEstimatedInterestAndFee($duration),
                'total_earned_interest_and_fee_($)' => $this->getEarnedInterestAndFee($duration),
                'period' => $duration,
            ),
            'timesheet' => $this->getArrayOfDatesAccordingToDuration($duration),
            'url' => 'totalLending',
            'title' => 'Total Lending',
        );

        return $data;

    }

    public function arrearsOverview($duration)
    {

        $data = array(
            'metrics' => array(
                'number_of_loans_in_arrears' => $this->getTotalArrearLoanCount($duration),
                'problem_percentage_of_loan_book' => $this->getProblemPercentageOfLoanBook($duration),
                'arrears_amount_($)' => $this->getArrearAmount($duration),
                'period' => $duration,
            ),
            'timesheet' => $this->getArrayOfDatesAccordingToDuration($duration),
            'url' => 'arrearsOverview',
            'title' => 'Arrears Overview',
        );

        return $data;

    }
    public function longTermDebtFundAnalysis($duration)
    {

        $data = array(
            'metrics' => array(
                'long_term_debt_fund_($)' => $this->approvedLoans($duration),
                'total_recovered_funds_($)' => $this->getTotalPrincipalValue($duration),
                'percentage_of_recovery' => $this->getTotalEstimatedInterestAndFee($duration),
                'period' => $duration,
            ),
            'timesheet' => $this->getArrayOfDatesAccordingToDuration($duration),
            'url' => 'longTermDebtFundAnalysis',
            'title' => 'Long Term Debt Fund Analysis',
        );

        return $data;

    }
    public function originationOverview($duration)
    {

        $data = array(
            'metrics' => array(
                'loan_originations_completed' => $this->approvedLoans($duration),
                'loan_originations_failed_or_withdrawn' => $this->getTotalPrincipalValue($duration),
                'percentage' => $this->getTotalEstimatedInterestAndFee($duration),
                'period' => $duration,
            ),
            'timesheet' => $this->getArrayOfDatesAccordingToDuration($duration),
            'url' => 'originationOverview',
            'title' => 'Origination Overview',
        );

        return $data;

    }
    public function paymentCollectionsOverview($duration)
    {

        $data = array(
            'metrics' => array(
                'long_payment' => $this->approvedLoans($duration),
                'total_collection' => $this->getTotalPrincipalValue($duration),
                'percentage' => $this->getTotalEstimatedInterestAndFee($duration),
                'period' => $duration,
            ),
            'timesheet' => $this->getArrayOfDatesAccordingToDuration($duration),
            'url' => 'paymentCollectionsOverview',
            'title' => 'Payment & Collections Overview',
        );

        return $data;

    }
}