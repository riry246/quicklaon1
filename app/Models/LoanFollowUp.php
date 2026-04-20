<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanFollowUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_application_id',
        'case_number',
        'topic',
        'follow_up_by',
        'date',
        'priority',
        'status',
        'next_follow_up',
        'comments',
        'method',
    ];

    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class);
    }
}
