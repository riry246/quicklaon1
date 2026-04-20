<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanApplication extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userAttrs()
    {
        return $this->hasMany(UserAttr::class, 'user_id', 'user_id')
            ->where('application_id', $this->getKey());
    }

    public function loanFollowUps()
    {
        return $this->hasMany(LoanCase::class)->orderBy('id', 'desc');
    }
    public function loanLatestFollowUps()
    {
        return $this->hasOne(LoanCase::class)->latest();
    }

    public function loanServiceAbility()
    {
        return $this->hasOne(ServiceAbility::class)->latest();
    }

    public function documents()
    {
        return $this->hasMany(Documentation::class);
    }
    public function statement()
    {
        return $this->hasMany(LoanStatement::class)->where('parent_id', null);
    }
    public function statements()
    {
        return $this->hasMany(LoanStatement::class);
    }
    public function rescheduledstatement()
    {
        return $this->hasMany(LoanStatement::class)->where('parent_id', '!=', null);
    }

    public function dishournedStatement()
    {
        return $this->hasMany(LoanStatement::class)->whereIn('payment_status', ['Dishonoured']);
    }
    public function unpaidstatement()
    {
        return $this->hasMany(LoanStatement::class)
            ->whereNotIn('payment_status', ['WaitingOnClearedFunds', 'Complete', 'Dishonoured', 'crs']);
    }
    public function totalLoanOutstanding()
    {
        return $this->hasMany(LoanStatement::class)
            ->whereIn('payment_status', ['WaitingOnClearedFunds', 'Re-scheduled','Scheduled','Hold']);
    }
    public function totalLoanAmountDateWise($date_from, $date_to)
    {
        return $this->hasMany(LoanStatement::class)
        ->whereIn('payment_status', ['WaitingOnClearedFunds', 'Complete', 'Re-scheduled','Scheduled','Hold','Dishonoured'])
        ->whereBetween('settlement_date', [$date_from, $date_to]);
    }
    public function totalLoanAmount()
    {
        return $this->hasMany(LoanStatement::class)
            ->whereIn('payment_status', ['WaitingOnClearedFunds', 'Complete', 'Re-scheduled','Scheduled','Hold']);
    }

    public function getLatestPaidStatement()
    {
        return $this->hasOne(LoanStatement::class)
            ->where('payment_status', 'Complete')
            ->latest('updated_at');
    }
    public function geLastSettlementDate()
    {
        return $this->hasOne(LoanStatement::class)
            ->latest('settlement_date');
    }
    public function getFirstStatement()
    {
        return $this->hasOne(LoanStatement::class);
    }
    public function getLatestDishonouredStatement()
    {
        return $this->hasOne(LoanStatement::class)
            ->where('payment_status', 'Dishonoured')
            ->latest('updated_at');
    }

    public function paidstatement()
    {
        return $this->hasMany(LoanStatement::class)
            ->whereIn('payment_status', ['WaitingOnClearedFunds', 'Complete']);
    }
    public function completestatement()
    {
        return $this->hasMany(LoanStatement::class)
            ->whereIn('payment_status', ['Complete']);
    }
    public function completestatementDateRange($date_from, $date_to)
{
    return $this->hasMany(LoanStatement::class)
        ->where('payment_status', 'Complete')
        ->whereBetween('settlement_date', [$date_from, $date_to]);
}
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'application_id');
    }
    public function creditScore()
    {
        return $this->hasMany(CreditScore::class, 'application_id');
    }
    public function riskScore()
    {
        return $this->hasOne(IllionCreditCheck::class, 'application_id');
    }
    public function latestcreditScore()
    {
        return $this->hasOne(CreditScore::class, 'application_id')->latest();
    }
    public function activeBankAccounts()
    {
        return $this->hasOne(BankAccount::class, 'application_id')->where('status', 'active');
    }
    public function latestcontract()
    {
        return $this->hasOne(CashFasterContract::class, 'application_id')->whereNotin('status', ['customer.signed', 'completed',])->latest();
    }
    public function latestcontractStatus()
    {
        return $this->hasOne(CashFasterContract::class, 'application_id')->latest();
    }
    public function contractList()
    {
        return $this->hasMany(CashFasterContract::class, 'application_id')->latest();
    }

    public function sms()
    {
        return $this->hasMany(Message::class, 'loan_application_id')->where('type', 'sms');
    }
    public function email()
    {
        return $this->hasMany(Message::class, 'loan_application_id')->where('type', 'mail');
    }
    public function inapp()
    {
        return $this->hasMany(Message::class, 'loan_application_id')->where('type', 'inapp');
    }

    public function leadMarket()
    {
        return $this->hasOne(LeadMarket::class, 'loan_application_id');
    }
    public function leadMarketBuy()
    {
        return $this->hasOne(LeadMarketBuy::class, 'loan_application_id');
    }
    public function applicationStep()
    {
        return $this->belongsTo(Step::class, 'step');
    }
    public function illionCustomerInfo()
    {
        return $this->hasOne(IllionCustomerInfo::class, 'loanapplication_id');
    }
    public function illionServiceAbility()
    {
        return $this->hasOne(IllionServiceAbility::class, 'application_id');
    }
    public function illionBankAccount()
    {
        return $this->hasMany(IllionBankAccount::class, 'loanapplication_id')->where('status', '1');
    }
    public function illionPrimaryAccount()
    {
        return $this->hasone(IllionBankAccount::class, 'loanapplication_id')->where('primary_account', 1)->where('status', '1');
    }

    public function viewedByUser()
    {
        return $this->belongsTo(User::class, 'viewed_by_user_id');
    }

}
