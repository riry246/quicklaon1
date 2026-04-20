<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documentation extends Model
{
    use HasFactory;

    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class);
    }
}
