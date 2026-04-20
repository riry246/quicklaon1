<?php

namespace App\Http\Controllers\Admin\Illion;

use App\Http\Controllers\Controller;
use App\Models\IllionServiceAbility;
use App\Models\LoanApplication;
use App\Traits\IllionTrait;
use Illuminate\Http\Request;
use App\Models\Factor;

class IllionServiceAbilityController extends Controller
{
    use IllionTrait;

    public function index($id)
    {
        $loanApplication = LoanApplication::findOrFail($id);
        $factor = Factor::all();
        $affordability = $this->generateConsumerAffordability($id);
        $data['metrics'] = [];

        foreach ($affordability as $k => $v) {
            foreach ($factor as $f) {
                if (lcfirst($f->value) == lcfirst($k)) {

                    if ($k == 'Wages') {
                        $attr = 'wages_after_tax';
                    } elseif ($k == 'CenterLink') {
                        $attr = 'centerlink_after_tax';
                    } else {
                        $attr = 'expenses_' . lcfirst($k);
                    }

                    if ($f->group_name) {
                        $data['metrics'][$f->type][$f->group_name][$k] = $v;
                        $data['metrics'][$f->type][$f->group_name][$k]['decleared'] = $this->getUserAttr($id, $attr);
                    } else {
                        $data['metrics'][$f->type][$k] = $v;
                        $data['metrics'][$f->type][$k]['decleared'] = $this->getUserAttr($id, $attr);

                    }

                }
            }
        }

        $data['summary'] = [
            'average_monthly_income' => $this->calculateIncomeAverage($data['metrics']['income'] ?? []),
            'average_monthly_expenses' => $this->calculateExpensesAverage($data['metrics']['expenses'] ?? []),
        ];

        $data['summary']['surplus'] = $data['summary']['average_monthly_income'] - abs($data['summary']['average_monthly_expenses']);

        
        $loans_detail = $this->getNumberOfLoans($data['metrics']['expenses'] ?? []);

        $data['summary']['no_of_active_loans'] = $loans_detail['number_of_active_loans'];
        $data['summary']['average_loan_debt_amount'] = $loans_detail['average_loan_debt_amount'];

        if (isset($data['metrics']['expenses']['others']['SACC Loans'])) {
            $data['loans']['SACC Loans'] = $data['metrics']['expenses']['others']['SACC Loans'];
        }
        if (isset($data['metrics']['expenses']['others']['Non SACC Loans'])) {
            $data['loans']['Non SACC Loans'] = $data['metrics']['expenses']['others']['Non SACC Loans'];
        }


        $illionServiceAblity = IllionServiceAbility::firstOrNew(['application_id' => $id]);
        $illionServiceAblity->user_id = $loanApplication->user_id;
        $illionServiceAblity->application_id = $loanApplication->id;
        $illionServiceAblity->summary = json_encode($data['summary']);
        $illionServiceAblity->income = json_encode($data['metrics']['income']);
        $illionServiceAblity->expenses = json_encode($data['metrics']['expenses']);
        $illionServiceAblity->loans = json_encode($data['loans']);
        $illionServiceAblity->save();

        return redirect()->back()->with('success', 'Success');
    }

    private function calculateIncomeAverage($metrics)
    {
        $monthlyAmountAverage = 0;

        foreach ($metrics as $items) {
            foreach ($items as $item) {
                if (isset($item['analysisCategory']['analysisPoints'])) {
                    foreach ($item['analysisCategory']['analysisPoints'] as $point) {
                        if ($point['name'] === 'monthlyAmountAverage') {
                            $monthlyAmountAverage += $point['value'];
                            break 2; // Break both loops once the value is found
                        }
                    }
                }
            }
        }

        return $monthlyAmountAverage;
    }

    private function calculateExpensesAverage($metrics)
    {
        $monthlyAmountAverage = 0;

        foreach ($metrics as $expenses) {

            foreach ($expenses as $items) {
                foreach ($items as $item) {
                    if (isset($item['analysisCategory']['analysisPoints'])) {
                        foreach ($item['analysisCategory']['analysisPoints'] as $point) {
                            if ($point['name'] === 'monthlyAmountAverage') {
                                $monthlyAmountAverage += $point['value'];
                                break 2; // Break both loops once the value is found
                            }
                        }
                    }
                }
            }
        }

        return $monthlyAmountAverage;
    }
    private function getNumberOfLoans($metrics)
    {
        $numberOfActiveLoans = 0;
        $averageLoanDebitAmount = 0;

        foreach ($metrics as $expenses) {
            foreach ($expenses as $items) {
                foreach ($items as $item) {
                    if (isset($item['analysisCategory'])) {
                        if ($item['analysisCategory']['name'] == 'SACC Loans' || $item['analysisCategory']['name'] == 'Non SACC Loans') {
                            if (isset($item['analysisCategory']['analysisPoints'])) {
                                foreach ($item['analysisCategory']['analysisPoints'] as $point) {
                                    if ($point['name'] === 'countOfOngoingLoans') {
                                        $numberOfActiveLoans += $point['value'];
                                    }
                                    if ($point['name'] === 'averageDebitAmount') {
                                        $averageLoanDebitAmount += $point['value'];

                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return array('number_of_active_loans' => $numberOfActiveLoans, 'average_loan_debt_amount' => $averageLoanDebitAmount);
    }
}
