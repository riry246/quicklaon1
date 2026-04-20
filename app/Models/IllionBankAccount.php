<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IllionBankAccount extends Model
{
    use HasFactory;
    protected $fillable = [
        'customerId',
        'account_number'
    ];
}
