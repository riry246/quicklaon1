<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'user_id',
        'loan_statements_id',
        'amount',
        'type',
        'duration_ms',
        'status',
        'status_description',
        'bpay_receipts',
        'caller_unique_reference',
        'fee_amount_excluding_gst',
        'fee_amount_gst_component',
        'fee_amount_including_gst',
        'fee_breakdown',
        'transaction_id',
    ];

    public function statements()
    {
        return $this->hasMany(LoanStatement::class,'transaction_id');
    }
    
}
