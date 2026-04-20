<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\LoanStatement;
use App\Models\MonoovaAccount;
use App\Models\User;
use App\Traits\BasiqTrait;
use App\Traits\GeneralTrait;
use App\Traits\LoanTrait;
use App\Traits\MessageTrait;
use App\Traits\MonoovaTrait;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use GuzzleHttp\Exception\ConnectException;
use Exception;
use Illuminate\Support\Facades\Log;

class CronjobController extends Controller
{
    use MonoovaTrait, GeneralTrait, LoanTrait, BasiqTrait, MessageTrait;

    public function CheckLoanStatus($status){

        $loanApplication = LoanApplication::where('status',$status)->get();
       
        

    }


}
