<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanStatement;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\GeneralTrait;
use App\Traits\MonoovaTrait;
use App\Traits\TableBuilderTrait;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    use GeneralTrait, TableBuilderTrait, MonoovaTrait;
    private $module_name = 'Transaction Management';
    private $title = 'Transaction';
    private $module = 'transaction';
    private $url = 'transaction.redirect';
    public function index($status)
    {

        $data['tabel_fields'] = array(
            'id',
            'transaction_id',
            'description',
            'status_description',
            'type',
            'amount',
            'status',
            'created_at'
        );
        //Page Title
        $data['pageTitle'] = $this->pageTitle($this->title, $this->module_name);

        //Button
        $data['btn'] = $this->disableButton($this->module);

        //Manage Action Button
        $data['action_btn'] = $this->actionViewButton($this->module);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Transaction', $this->url, null);

        if ($status == 'all') {
            $data['list'] = Transaction::orderby('id', 'desc')->get();
        } else {
            $data['list'] = Transaction::where('status', $status)->orderby('id', 'desc')->get();
        }

        return view('admin.transaction.list', $data);

    }
    public function principal()
    {

        $data['tabel_fields'] = array(
            'id',
            'transaction_id',
            'description',
            'status_description',
            'type',
            'amount',
            'status',
            'created_at'
        );
        //Page Title
        $data['pageTitle'] = $this->pageTitle($this->title, $this->module_name);

        //Button
        $data['btn'] = $this->disableButton($this->module);

        //Manage Action Button
        $data['action_btn'] = $this->actionViewButton($this->module);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Principal Deposited', $this->url, null);

        $data['list'] = Transaction::where('account_number', '802985425628199')->orderby('id', 'desc')->get();
        $data['amount'] = Transaction::where('account_number', '802985425628199')->sum('amount');
        
        return view('admin.transaction.listprinciple', $data);

    }
    public function transactionByUserID($id)
    {

        $data['tabel_fields'] = array(
            'id',
            'transaction_id',
            'description',
            'status_description',
            'type',
            'amount',
            'status',
            'created_at'
        );
        //Page Title
        $data['pageTitle'] = $this->pageTitle($this->title, $this->module_name);

        //Button
        $data['btn'] = $this->disableButton($this->module);

        //Manage Action Button
        $data['action_btn'] = $this->actionViewButton($this->module);

        $user = User::find($id);
        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, $this->getUserName($id), $this->url, null);

        $data['list'] = Transaction::where('user_id', $id)->orderby('id', 'desc')->get();
        $data['id'] = $id;


        return view('admin.userTransaction.list', $data);

    }

    public function viewDetail($id)
    {
        $data['breadcrumb'] = $this->breadcrumb('Transaction Detail', 'Detail', $this->url, null);
        $data['transaction'] = Transaction::where('id', $id)->with('statements')->first();

        return view('admin.transaction.view', $data);

    }

    public function updateTransactionStatus($transaction_id)
    {

        $transaction = Transaction::find($transaction_id);

        $paymentStatus = $this->checkStatus($transaction->caller_unique_reference);

        $transaction->status_description = $paymentStatus['statusDescription'];
        $transaction->status = $paymentStatus['transactionStatus'];
        $transaction->completed_date = $paymentStatus['completedDate'];
        $transaction->credit_card_payment_status = $paymentStatus['creditCardPaymentStatus'];
        $transaction->dishonoured_date = $paymentStatus['dishonouredDate'];
        $transaction->expected_clearance_date_for_funds = $paymentStatus['expectedClearanceDateForFunds'];
        $transaction->funds_cleared_date = $paymentStatus['fundsClearedDate'];
        $transaction->save();

        if ($transaction->loan_statements_id) {
            $statement = LoanStatement::find($transaction->loan_statements_id);
            $statement->payment_status = $paymentStatus['transactionStatus'];
            $statement->save();
        }

        return redirect()->back()->with('success', $paymentStatus['statusDescription']);

    }
}
