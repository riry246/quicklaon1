<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonoovaAccount extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'status',
        'statusDescription',
        'bankAccountName',
        'bankAccountNumber',
        'bsb',
        'clientUniqueId',
        'isActive',
        'payid'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
