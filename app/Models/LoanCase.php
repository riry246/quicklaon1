<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanCase extends Model
{
    use HasFactory;

    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class);
    }

    public function assignmentHistories()
    {
        return $this->hasMany(CaseAssignmentHistory::class, 'case_id');
    }
    public function followupHistories()
    {
        return $this->hasMany(CaseFollowUpsHistory::class, 'case_id')->orderBy('id', 'desc');;
    }
}
