<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class)->withTimestamps();
    }

    public function userAttrValues()
    {
        return $this->hasMany(UserAttr::class);
    }

    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class);
    }
    public function countLoans()
    {
        return $this->hasMany(LoanApplication::class);
    }
    public function completedLoans()
    {
        return $this->hasMany(LoanApplication::class)->where('status', 'completed');
    }
    public function idVerifcation()
    {
        return $this->hasMany(UserIdVerification::class)->orderBy('created_at', 'desc');
    }
    public function rejectedidVerifcation()
    {
        return $this->hasMany(UserIdVerification::class)->where('status', 'rejected')->orderBy('created_at', 'desc');
    }

    public function referral()
    {
        return $this->hasOne(Referral::class);
    }
    public function cashfasterScores()
    {
        return $this->hasMany(CashfasterScore::class, 'user_id', 'id');
    }

    public function hasPermission($permission)
    {
        // Check if the user has the specified permission
        foreach ($this->groups as $userGroup) {
            foreach ($userGroup->permissions as $userPermission) {
                $action = $userPermission->moduleAction->action;
                $module = $userPermission->moduleAction->module->name;
                // dd($action,$module,$permission);
                logger()->error('permission', ['action' => $action . ' ' . $module, 'permission' => $permission]);
                if ($action . ' ' . $module === $permission) {
                    return true;
                }
            }
        }
    }

    public function fcmTokens()
    {
        return $this->hasMany(FcmToken::class);
    }

    public function monoovaAccount()
    {
        return $this->hasOne(MonoovaAccount::class);
    }
    public function bankStatements()
    {
        return $this->hasMany(BankStatement::class);
    }
    public function latestbankStatements()
    {
        return $this->hasOne(BankStatement::class)->where('type', 'normal')->latest();
    }
    public function latestAffordabilitybankStatements()
    {
        return $this->hasOne(BankStatement::class)->where('type', 'affordability')->latest();
    }
    public function latestConsumerAffordabilitybankStatements()
    {
        return $this->hasOne(BankStatement::class)->where('type', 'report')->latest();
    }

    public function setGoogle2FaSecretAttribute($value)
    {
        $this->attributes['google2fa_secret'] = encrypt($value);
    }
    public function getGoogle2FaSecretAttribute($value)
    {
        return decrypt($value);
    }
    public function latestLoanApplication()
    {
        return $this->hasOne(LoanApplication::class)->where('status', 'active')->latest();
    }

   

    

}
