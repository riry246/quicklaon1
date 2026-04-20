<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LoanTerm;
use Illuminate\Http\Request;

class LoanTermController extends Controller
{
    public function index($slug = "terms-and-conditions")
    {
        $loanTerms = LoanTerm::where('status', 'active')->where('slug', $slug)->first();

        return response()->json([
            "success" => true,
            "message" => "Loan Term List",
            "data" => $loanTerms
        ], 200);
    }
}
