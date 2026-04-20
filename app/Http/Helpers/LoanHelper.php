<?php

namespace App\Http\Helpers;

use App\Models\IllionBank;
use App\Models\IllionCost;
use App\Models\IllionCreditCheck;
use App\Models\IllionCustomerInfo;
use App\Models\Industry;
use App\Models\LoanApplication;
use App\Models\LoanStatement;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use DateTime;

class LoanHelper
{
    protected $model;

    public function __construct(LoanApplication $model)
    {
        $this->model = $model;
    }

    public function generateCaseNumber()
    {
        $prefix = 'CF';
        $date = now()->format('Ymd'); // Format the current date as YYYYMMDD
        $uniqueIdentifier = uniqid(); // Generate a unique identifier

        $caseNumber = $prefix . '-' . $date . '-' . $uniqueIdentifier;

        return $caseNumber;
    }

    public function getAdminUsers()
    {
        $user = User::whereDoesntHave('groups', function ($query) {
            $query->where('slug', 'customer');
        })->with('groups')->get();

        return $user;
    }

    public function getUser($id)
    {
        $user = User::findorfail($id);
        return $user;
    }

    public function getLatestAssinged($case_id)
    {

    }

    public function getIndustry($code)
    {
        $industry = Industry::where('code', $code)->first();
        if ($industry) {
            return $industry->name;
        }
        return false;
    }

    public function getUserName($id)
    {
        $user = User::find($id);
        if ($user) {
            return $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name;
        }
        return null;

    }
    public function getUserNameByAppID($id)
    {
        $loanId = LoanApplication::find($id);
        $user = User::find($loanId->user_id);
        if ($user) {
            return $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name;
        }
        return null;

    }
    public function getUserNameByMobile($id)
    {
        $user = User::where('mobile',$id)->first();
        if ($user) {
            return $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name;
        }
        return null;

    }
    public function getUserfirstName($id)
    {
        $user = User::find($id);
        if ($user) {
            return $user->first_name;
        }
        return null;

    }

    public function formateDate($date)
    {
        if ($date) {
            $timestamp = strtotime($date);
            return date('d-m-Y', $timestamp);
        }
        return;
    }
    public function formateDateDynamic($date, $format)
    {
        if ($date) {
            $timestamp = strtotime($date);
            return date($format, $timestamp);
        }
        return;
    }
    public function formateTime($date)
    {
        $timestamp = strtotime($date);
        return date('h:i a', $timestamp);
    }
    public function formateDateTime($date)
    {
        $timestamp = strtotime($date);
        return date('d-m-Y h:i a', $timestamp);
    }

    function convertDateTimeToRelativeFormat($startTime)
    {
        $endTime = new DateTime();
        $interval = $startTime->diff($endTime);

        if ($interval->days > 0) {
            // If more than 24 hours, show days
            if ($interval->days > 1) {
                return "{$interval->days} days ago";
            } else {
                return "{$interval->days} day ago";
            }

        } else {
            // If within 24 hours, show hours and minutes
            return $interval->format('%hh %im') . ' ago';
        }
    }

    function getTransactionStatus()
    {
        $latestTransactions = Transaction::orderBy('status')->get();
        $groupedStatuses = $latestTransactions->groupBy('status');
        $result = $groupedStatuses->toArray();

        return $result;
    }

    function formatCurrencynormal($amount)
    {
        $amount = (float) $amount;

        $formattedAmount = number_format(abs($amount), 2);
        return ($amount < 0 ? '-' : '') . $formattedAmount;
    }
    function formatCurrency($amount)
    {
        $amount = (float) $amount;

        $formattedAmount = number_format(abs($amount), 2);
        return ($amount < 0 ? '-$ ' : '$ ') . $formattedAmount;
    }
    function formatCurrencyabs($amount)
    {
        $amount = (float) $amount;

        $formattedAmount = number_format(abs($amount), 2);
        return ($amount < 0 ? '$ ' : '$ ') . $formattedAmount;
    }
    function highlightKeywords($text, $keyword)
    {
        return preg_replace('/(' . preg_quote($keyword, '/') . ')/i', '<span class="bg-warning-transparent">$1</span>', $text);
    }

    function roundNumber($number)
    {
        $roundedNumber = number_format($number, 2);
        return $roundedNumber;
    }

    function getStatementTotalByStatus($loan_id, $statuses)
    {
        $total = LoanStatement::where('loan_application_id', $loan_id)
            ->whereIn('payment_status', $statuses)
            ->sum('weekly_payment');

        return $this->formatCurrency($total);
    }

    function checkRiskFactor($user_id)
    {
        $risk_factor = 'normal';

        $user = User::find($user_id);
        $numberOfLoans = $user->countLoans()->get();
        $numberOfCompletedLoans = $user->completedLoans()->count();
        $numberOfDishourned = 0;

        foreach ($numberOfLoans as $loan) {
            $numberOfDishourned += count($loan->dishournedStatement()->get());
        }


        if ($numberOfCompletedLoans > 0 && $numberOfDishourned < 3) {
            $risk_factor = 'low';
        } elseif ($numberOfCompletedLoans > 0 && $numberOfDishourned < 6) {
            $risk_factor = 'medium';
        }

        return $risk_factor;

    }

    function getBankImage($name)
    {
        $bank = IllionBank::where('name', $name)->first();

        if ($bank) {
            return 'https://www.bankstatements.com.au/images/institutions/' . $bank->slug . '.png';
        } else {
            return false;
        }

    }
    public static function beautifyVariableName($name)
    {
        // Split the camelCase string into words
        $words = preg_split('/(?=[A-Z])/', $name);

        // Capitalize the first letter of each word and join them with a space
        $title = implode(' ', $words);

        return ucfirst($title);
    }

    public function getItrs($id)
    {
        $score = 0;
        $illion = IllionCustomerInfo::where('loanapplication_id', $id)->first();
        if ($illion) {
            if ($illion->scoreModels) {
                $score = json_decode($illion->scoreModels);
                foreach ($score as $s) {
                    $score = $s->modelScore;
                }
            }
        }

        return $score;

    }
    public function costOfApplication($app)
    {

        $illionCustomerInfo = IllionCustomerInfo::where('loanapplication_id', $app->id)->get();
        $illionCreditCheck = IllionCreditCheck::where('application_id', $app->id)->get();

        foreach ($illionCustomerInfo as $cx) {
            $statmentCost = IllionCost::where('customerId', $cx->customerId)->get();
            foreach ($statmentCost as $cost) {
                $cost->user_id = $app->user_id;
                $cost->loanapplication_id = $app->id;
                $cost->save();
            }

        }

        foreach ($illionCreditCheck as $cx) {
            $statmentCost = IllionCost::where('customerId', $cx->consumer_id)->first();
            if (!$statmentCost) {
                $cost = new IllionCost();
                $cost->loanapplication_id = $cx->application_id;
                $cost->user_id = $cx->user_id;
                $cost->customerId = $cx->consumer_id;
                $cost->amount = 3.5;
                $cost->type = 'Credit Check';
                $cost->save();
            }
        }

        $cost = IllionCost::where('loanapplication_id', $app->id)->get();

        return $cost->sum('amount');

    }
}
