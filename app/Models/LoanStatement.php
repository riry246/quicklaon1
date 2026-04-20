<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanStatement extends Model
{
    use HasFactory;
    protected $fillable = [
        'loan_application_id',
        'opening_balance',
        'weekly_payment',
        'interest',
        'principal_payment',
        'closing_balance',
        'payment_date',
        'settlement_date',
        'payment_status',
        'late_fee',
        'reschedule_fee',
        'parent_id',
        'frequency',
        // Add other fields as needed
    ];
    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class);
    }
    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'loan_statements_id');
    }
    public function latestTransaction()
    {
        return $this->hasOne(Transaction::class, 'loan_statements_id')->latest();
    }
    public function statements()
    {
        return $this->hasMany(LoanStatement::class, 'loan_statements_id');
    }
}
