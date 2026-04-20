<?php

namespace App\Http\Controllers\Api;

use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bank;

class BankController extends Controller
{
    use GeneralTrait;

    public function index()
    {
        $banks = Bank::where('status', 'active')->get();

        return response()->json([
            "success" => true,
            "message" => "Bank List",
            "data" => $banks
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $result = $this->createBank($data);

        if ($result) {
            return response()->json([
                "success" => true,
                "message" => "Bank Created Successfully",
                "data" => $result
            ], 200);
        }
    }
}
