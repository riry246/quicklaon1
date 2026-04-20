<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpensesAnalysis;
use App\Models\IncomeAnalysis;
use App\Models\LoanApplication;
use App\Models\SaccLoanAnalysis;
use App\Models\Factor;
use App\Models\ServiceAbility;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class ServiceAbilityController extends Controller
{
    use GeneralTrait;

    public function view($basiq_user_id, $id)
    {


        $data['breadcrumb'] = $this->breadcrumb('Serviceability Report', 'Report', 'analytics.index', null);
        $loanApplication = LoanApplication::find($id);
        $statement = $loanApplication->loanServiceAbility;
        $data['statements'] = json_decode($statement->report);
        $data['summary'] = [];
        $data['loans'] = [];
        $data['latestcreditScore'] = $loanApplication->latestcreditScore;

        //Get total factor wise
        $factor = Factor::all();
        foreach ($factor as $f) {
            $type = $f->type;
            $factor = $f->value;
            if ($type == 'expenses') {
                $data['summary'][$type]['summary']['total'] = 0;
                foreach ($data['statements']->$type as $key => $value) {
                    foreach ($value as $k => $e) {
                        if (isset($e->analysis)) {
                            $data['summary'][$type][$e->title]['total'] = $e->analysis->amount->total;
                            $data['summary'][$type][$e->title]['frequency'] = $e->analysis->frequency->display;
                            $data['summary'][$type]['summary']['total'] += $e->analysis->amount->total;

                        }
                        if ($key == 'Liabilities') {
                            $data['loans'][$e->title] = $e;
                        }
                    }
                }

            } else {
                $data['summary'][$type]['summary']['total'] = 0;
                foreach ($data['statements']->$type as $s) {
                    if (isset($s->analysis)) {
                        $data['summary'][$type][$s->title]['total'] = $s->analysis->amount->total;
                        $data['summary'][$type][$s->title]['frequency'] = $s->analysis->frequency->display;
                        $data['summary'][$type]['summary']['total'] += $s->analysis->amount->total;
                    }
                }
            }
        }
        $data['noOfLoans'] = 0;
        $data['loanRepayments'] = 0;

        foreach ($data['loans'] as $k => $v) {
            if ($v->analysis->amount->total !== '0.00') {
                foreach ($v->subgroup as $sub) {

                    if ($sub->analysis->frequency->next->date) {
                        $data['noOfLoans'] += 1;
                        if ($sub->analysis->frequency->display == "Weekly") {
                            $data['loanRepayments'] += $sub->analysis->frequency->next->amount * 4;
                        } else if ($sub->analysis->frequency->display == "Fortnightly") {
                            $data['loanRepayments'] += $sub->analysis->frequency->next->amount * 2;
                        } else {
                            $data['loanRepayments'] += $sub->analysis->frequency->next->amount;
                        }


                    }

                }
            }


        }
        // dd($data);
        $data['loanapplication'] = $loanApplication;
        return view('admin.serviceAbility.report', $data);

    }
    public function index(Request $request)
    {
        try {
            ini_set('memory_limit', '64M');
            $basiq_user_id = $request->input('basiq_user_id');
            $id = $request->input('id');
          
            $report = null;
            $accounts = null;
            $data = null;

            $user = User::where('basiq_user_id', $basiq_user_id)->firstOrFail();
            $consumerReport = $user->latestConsumerAffordabilitybankStatements;

            if ($consumerReport) {
                $report = json_decode($consumerReport->statement);
                $accounts = json_decode($consumerReport->accounts);

                $data['id'] = $report->id;
                $data['title'] = $report->title;
                $data['createdDate'] = $report->createdDate;
                $data['filters'] = $report->filters;

                $groupedGroups = [];
                foreach ($report->data->groups as $item) {
                    $section = reset($item->sections); // Assuming each item has one section
                    $groupedGroups[$section][] = $item;
                }


                $factor = Factor::all();
                foreach ($factor as $f) {
                    if ($f->type == 'expenses') {
                        $data[$f->type][$f->value] = $this->getLiablitesFactor($groupedGroups, $f->value);
                    } else {
                        $data[$f->type][$f->value] = $this->getFactor($groupedGroups, $f->value);
                    }

                }

                $serviceAbility = new ServiceAbility();
                $serviceAbility->user_id = $user->id;
                $serviceAbility->loan_application_id = $id;
                $serviceAbility->report = json_encode($data);
                $serviceAbility->save();
            }

            return response()->json(['success' => 'Service Ability Report generated successfully'], 200);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            print_r($e);
            die();
           // return response()->json(['error' => 'An error occurred while processing the request.'], 500);
        }
    }


    public function getFactor($report, $factor)
    {
        $total = 0;
        $data = [];
        $combinedArray = [];

        // Loop through each sub-array in $groupedGroups and add its elements to $combinedArray
        foreach ($report as $subArray) {
            $combinedArray = array_merge($combinedArray, $subArray);
        }

        foreach ($combinedArray as $r) {

            if ($r->title == $factor) {
                $data = $r;
            }
        }
        return $data;
    }
    public function getLiablitesFactor($report, $factor)
    {
        $total = 0;
        $data = [];
        $combinedArray = [];

        // Loop through each sub-array in $groupedGroups and add its elements to $combinedArray
        foreach ($report as $subArray) {
            $combinedArray = array_merge($combinedArray, $subArray);
        }

        foreach ($report[$factor] as $r) {
            $variable = $r->sections[0];
            if ($variable == $factor) {
                $data[$r->title] = $r;
            }
        }
        return $data;
    }


}
