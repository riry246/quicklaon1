<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\LoanStatement;
use App\Traits\GeneralTrait;
use App\Traits\LoanTrait;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoanStatementController extends Controller
{
    use GeneralTrait, LoanTrait;
    private $module_name = 'Loan Statement';
    private $module = 'loanStatement';
    private $url = 'loan.index';

    public function index($user_id, $loan_id)
    {
        try {
            $data['breadcrumb'] = $this->breadcrumb('Loan Statement', 'Detail', $this->url, null);
            $loan = LoanApplication::find($loan_id);
            $data['loanapplication'] = $loan;
            $data['loanstatement'] = $loan->statements->sortBy('settlement_date');
            $data['reschedule'] = $loan->rescheduledstatement;
            $data['user'] = $loan->user;


            return view('admin.loanStatement.index', $data);


        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function reschedulePayment($user_id, $id)
    {
        try {
            $loanStatement = LoanStatement::find($id);

            $loan = LoanApplication::find($loanStatement->loan_application_id);

            $this->createReschedulePayment($loanStatement);
            return redirect()->back()->with('success', 'Payment schedule for Statement ID ' . $id . ' has been successfully re-scheduled. The new payment details are updated.');



        } catch (\Exception $e) {
            dd($e);
        }
    }
    public function updateStatement(Request $request, $user_id, $id)
    {
        try {
            $loanStatement = LoanStatement::find($id);
            $settlement_date = $request->settlement_date;
            $payment_status = $request->payment_status;
            $amount = $request->amount;

            $loan = LoanApplication::find($loanStatement->loan_application_id);

            if ($payment_status == 'Rescheduled&charge') {
                $this->createReschedulePaymentManually($loanStatement, $payment_status, $settlement_date);
            } elseif ($payment_status == 'Dishonoured&Rescheduled') {
                $this->createReschedulePaymentManually($loanStatement, $payment_status, $settlement_date);
            } else {
                $loanStatement->payment_status = $payment_status;
                $loanStatement->weekly_payment = $amount;
                $loanStatement->settlement_date = $settlement_date;
                $loanStatement->save();
            }

            return redirect()->back()->with('success', 'Payment schedule for Statement ID ' . $id . ' has been successfully re-scheduled. The new payment details are updated.');



        } catch (\Exception $e) {
            dd($e);
        }
    }
    public function partialUpdateStatement(Request $request, $user_id, $id)
    {
        try {

            $loanStatement = LoanStatement::find($id);
            $loanStatement->payment_status = 'crs';
            $max_amount = $loanStatement->weekly_payment;

            $amount1 = $request->amount1;
            $amount2 = $request->amount2;
            $settlement_date1 = $request->settlement_date_1;
            $settlement_date2 = $request->settlement_date_2;
            $total_amount = $amount1 + $amount2;

            if ($total_amount > $max_amount) {
                return redirect()->back()->with('error', 'Partial amount should not be more than the actuall amount of statement. (' . $loanStatement->weekly_payment . ')');
            }

            if ($total_amount < $max_amount) {
                return redirect()->back()->with('error', 'Partial amount should not be less than the actuall amount of statement. (' . $loanStatement->weekly_payment . ')');
            }



            $loanStatement->save();


            $this->createPartialPaymentManually($loanStatement, $amount1, $settlement_date1);
            $this->createPartialPaymentManually($loanStatement, $amount2, $settlement_date2);


            return redirect()->back()->with('success', 'Partial payment schedule for Statement ID ' . $id . ' has been successfully re-scheduled. The new payment details are updated.');



        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function reschdeuleWhole(Request $request, $id)
    {
        try {
            $loan = LoanApplication::find($id);

            $statement = $loan->statements;
            $paidstatement = $loan->paidstatement;

            //Loan detail
            $loanAmount = $loan->approved_amount;
            $durationInWeeks = $loan->duration;
            $date = $request->start_date;
            $frequency = $request->frequency;

            if (count($paidstatement) > 0) {
                return redirect()->back()->with('error', 'Unable to modify payment schedule. There are cleared payments associated with this schedule. Please update the schedule manually.');
            } else {
                $this->delete($statement);

                $loanStatement = $this->reGenerateLoanStatement($loanAmount, $durationInWeeks, $date, $frequency);

                foreach ($loanStatement as $ls) {
                    $statement = new LoanStatement();
                    $statement->loan_application_id = $loan->id;
                    $statement->payment_date = $ls['payment_date'];
                    $statement->settlement_date = $ls['settlement_date'];
                    $statement->opening_balance = $ls['opening_balance'];
                    $statement->weekly_payment = $ls['weekly_payment'];
                    $statement->interest = $ls['interest'];
                    $statement->principal_payment = $ls['principal_payment'];
                    $statement->closing_balance = $ls['closing_balance'];
                    $statement->payment_status = $ls['payment_status'];
                    $statement->weekly_establishment_fee = $ls['weekly_establishment_fee'];
                    $statement->weekly_interest = $ls['weekly_interest'];
                    $statement->frequency = $frequency;

                    $statement->save();
                }

                $loan->frequency = $frequency;
                $loan->save();
            }

            return redirect()->back()->with('success', 'Payment schedule has been successfully re-scheduled. The new payment details are updated.');

        } catch (\Exception $e) {
            dd($e);
        }

    }
    public function addNewPayment(Request $request, $id)
    {
        try {
            $loan = LoanApplication::find($id);

            //Loan detail
            $loanAmount = $request->amount;
            $number_of_statement = $request->no_of_statement;
            $date = Carbon::parse($request->start_date);
            $frequency = $request->frequency;
            $payment_status = $request->payment_status;

            // Determine the interval for payment dates based on frequency
            $interval = ($frequency === 'weekly') ? 1 : 2;

            $interest = 0;
            $principal_payment = 0;
            $weekly_establishment_fee = 0;
            $weekly_interest = 0;


            if ($payment_status !== "fee") {
                $weekly_establishment_fee = $loanAmount * 0.2;
                $weekly_interest = $loanAmount * 0.04;
                $interest = $weekly_establishment_fee + $weekly_interest;
                $principal_payment = $loanAmount - $interest;
            } else {
                $payment_status = 'Scheduled';
            }


            $payment_date = new \DateTime($date);

            for ($i = 0; $i < $number_of_statement; $i++) {
                $statement = new LoanStatement();
                $statement->loan_application_id = $loan->id;

                $statement->payment_date = $date->copy();
                $statement->settlement_date = $date->copy();
                $date->addWeeks($frequency === 'weekly' ? 1 : 2);

                $statement->weekly_payment = $loanAmount;
                $statement->opening_balance = 0;
                $statement->closing_balance = 0;

                $statement->interest = $interest;
                $statement->principal_payment = $principal_payment;
                $statement->weekly_establishment_fee = $weekly_establishment_fee;
                $statement->weekly_interest = $weekly_interest;

                $statement->payment_status = $payment_status;
                $statement->frequency = $frequency;

                $statement->save();
            }

            return redirect()->back()->with('success', 'Payment schedule has been successfully re-scheduled. The new payment details are updated.');

        } catch (\Exception $e) {
            dd($e);
        }

    }

    public function delete($statements)
    {
        foreach ($statements as $s) {
            if (in_array($s->payment_status, ['Scheduled', 'Upcoming Payment', 'Re-scheduled', 'crs', 'Late Payment'])) {
                LoanStatement::destroy($s->id);
            }
        }
        return true;
    }

}
