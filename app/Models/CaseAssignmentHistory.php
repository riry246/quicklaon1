<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseAssignmentHistory extends Model
{
    use HasFactory;

    public function loanCase()
    {
        return $this->belongsTo(LoanCase::class,'case_id');
    }
}
