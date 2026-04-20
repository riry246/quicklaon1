<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'public_code',
        'basiq_code',
        'illion_with_mfa',
        'short_name',
        'name',
        'full_name',
        'bank_tier',
        'crm_name',
        'username',
        'password',
        'secret',
        'basiq_stage',
        'basiq_status',
        'status',
        'logo'
    ];

    public function bankAccounts() {
        return $this->hasMany(BankAccount::class);
    }
}
