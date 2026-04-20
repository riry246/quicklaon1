<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseFollowUpsHistory extends Model
{
    use HasFactory;

    public function case ()
    {
        return $this->belongsTo(LoanCase::class, 'case_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'follow_up_by');
    }
}
