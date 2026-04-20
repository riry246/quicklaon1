<?php

namespace App\Http\Helpers;

use App\Models\Transaction;
use Carbon\Carbon;

class TransactionHelper
{
    public function todayTransaction()
    {
        // Get today's date
        $today = Carbon::today();

        // Fetch transactions for today
        $transactions = Transaction::whereDate('created_at', $today)->get();

        // Calculate total amount and count of transactions
        $totalAmount = $transactions->sum('amount');
        $transactionCount = $transactions->count();

        $totalDebit = $transactions->where('type', 'Debit')->sum('amount');
        $totalCredit = $transactions->where('type', 'Credit')->sum('amount');

        return [
            'transaction_count' => $transactionCount,
            'total_amount' => $totalAmount,
            'total_debit' => $totalDebit,
            'total_credit' => $totalCredit,
        ];
    }
    public function todayTransactionByUser($id)
    {
        // Get today's date
        $today = Carbon::today();

        // Fetch transactions for today
        $transactions = Transaction::whereDate('created_at', $today)->where('user_id',$id)->get();

        // Calculate total amount and count of transactions
        $totalAmount = $transactions->sum('amount');
        $transactionCount = $transactions->count();

        $totalDebit = $transactions->where('type', 'Debit')->sum('amount');
        $totalCredit = $transactions->where('type', 'Credit')->sum('amount');

        return [
            'transaction_count' => $transactionCount,
            'total_amount' => $totalAmount,
            'total_debit' => $totalDebit,
            'total_credit' => $totalCredit,
        ];
    }

    public function getTransactionsByStatus($status)
    {
        $transactions = Transaction::where('status', $status)->get();

        $totalAmount = $transactions->sum('amount');
        $transactionCount = $transactions->count();

        $totalDebit = $transactions->where('type', 'Debit')->sum('amount');
        $totalCredit = $transactions->where('type', 'Credit')->sum('amount');

        return [
            'transaction_count' => $transactionCount,
            'total_amount' => $totalAmount,
            'total_debit' => $totalDebit,
            'total_credit' => $totalCredit,
        ];
    }
    public function getTransactionsByStatusandID($status,$id)
    {
        $transactions = Transaction::where('status', $status)->where('user_id',$id)->get();

        $totalAmount = $transactions->sum('amount');
        $transactionCount = $transactions->count();

        $totalDebit = $transactions->where('type', 'Debit')->sum('amount');
        $totalCredit = $transactions->where('type', 'Credit')->sum('amount');

        return [
            'transaction_count' => $transactionCount,
            'total_amount' => $totalAmount,
            'total_debit' => $totalDebit,
            'total_credit' => $totalCredit,
        ];
    }

    public function getTransactionsfee()
    {
        $transactions = Transaction::all();
        
        $totalAmount = $transactions->sum('fee_amount_including_gst');
        $transactionCount = $transactions->count();

        $totalDebit = $transactions->where('type', 'Debit')->sum('amount');
        $totalCredit = $transactions->where('type', 'Credit')->sum('amount');

        return [
            'transaction_count' => $transactionCount,
            'total_amount' => $totalAmount,
            'total_debit' => $totalDebit,
            'total_credit' => $totalCredit,
        ];
    }
}
