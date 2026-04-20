<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LoanQuestion;
use Illuminate\Http\Request;

class LoanQuestionController extends Controller
{
    public function index()
    {
        $loanQuestions = LoanQuestion::where('status', 'active')->get();

        return response()->json([
            "success" => true,
            "message" => "Loan Question List",
            "data" => $loanQuestions
        ], 200);
    }
}
