<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\LoanStatement;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use App\Models\ApplicationStatement;

class SearchController extends Controller
{
    use GeneralTrait;
    private $module_name = 'Search';
    private $title = 'Search';
    private $module = 'search';
    private $url = 'search';

    public function search(Request $request)
    {
        $data['breadcrumb'] = $this->breadcrumb('Search Result', 'Search', $this->url, null);

        $keyword = $request->input('keyword');

        $data['keyword'] = $keyword;

        // Search for users
        $data['users'] = User::where('first_name', 'LIKE', "%$keyword%")
            ->orwhere('middle_name', 'LIKE', "%$keyword%")
            ->orwhere('last_name', 'LIKE', "%$keyword%")
            ->orwhere('id', 'LIKE', "%$keyword%")
            ->orwhere('mobile', 'LIKE', "%$keyword%")
            ->orWhere('email', 'LIKE', "%$keyword%")
            ->orWhere('status', 'LIKE', "%$keyword%")
            ->get();

        $data['transactions'] = Transaction::where('account_number', 'LIKE', "%$keyword%")
            ->orwhere('account_name', 'LIKE', "%$keyword%")
            ->orwhere('bsb', 'LIKE', "%$keyword%")
            ->orwhere('institution', 'LIKE', "%$keyword%")
            ->orwhere('application_id', 'LIKE', "%$keyword%")
            ->orWhere('loan_statements_id', 'LIKE', "%$keyword%")
            ->orWhere('amount', 'LIKE', "%$keyword%")
            ->orWhere('description', 'LIKE', "%$keyword%")
            ->orWhere('status_description', 'LIKE', "%$keyword%")
            ->orWhere('bpay_receipts', 'LIKE', "%$keyword%")
            ->orWhere('type', 'LIKE', "%$keyword%")
            ->orWhere('id', 'LIKE', "%$keyword%")
            ->orWhere('transaction_id', 'LIKE', "%$keyword%")
            ->get();

        $data['loan_applications'] = LoanApplication::where('id', 'LIKE', "%$keyword%")
            ->orwhere('user_id', 'LIKE', "%$keyword%")
            ->orwhere('contract_id', 'LIKE', "%$keyword%")
            ->orwhere('amount', 'LIKE', "%$keyword%")
            ->orwhere('duration', 'LIKE', "%$keyword%")
            ->orWhere('frequency', 'LIKE', "%$keyword%")
            ->orWhere('approved_amount', 'LIKE', "%$keyword%")
            ->orWhere('rejection_reason', 'LIKE', "%$keyword%")
            ->orWhere('status', 'LIKE', "%$keyword%")
            ->get();

        $data['statement'] = LoanStatement::where('id', 'LIKE', "%$keyword%")
            ->orwhere('loan_application_id', 'LIKE', "%$keyword%")
            ->orwhere('weekly_payment', 'LIKE', "%$keyword%")
            ->orwhere('transaction_id', 'LIKE', "%$keyword%")
            ->orwhere('payment_date', 'LIKE', "%$keyword%")
            ->orWhere('settlement_date', 'LIKE', "%$keyword%")
            ->orWhere('payment_status', 'LIKE', "%$keyword%")
            ->orWhere('frequency', 'LIKE', "%$keyword%")
            ->get();


        return view('admin.search.list', $data);
    }
}
