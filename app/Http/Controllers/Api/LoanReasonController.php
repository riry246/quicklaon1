<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LoanReason;
use Illuminate\Http\Request;

class LoanReasonController extends Controller
{
    public function index()
    {
        $loanReasons = LoanReason::where('status', 'active')->get();

        return response()->json([
            "success" => true,
            "message" => "Loan Reason List",
            "data" => $loanReasons
        ], 200);
    }
}
