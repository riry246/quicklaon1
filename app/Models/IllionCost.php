<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IllionCost extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'loanapplication_id',
        'customerId',
        'amount',
        'type',
        'created_at'
    ];
}
