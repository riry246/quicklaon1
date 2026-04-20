<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CaseAssignmentHistory;
use App\Models\CaseFollowUpsHistory;
use App\Models\LoanApplication;
use App\Models\LoanCase;
use App\Traits\GeneralTrait;
use App\Traits\MessageTrait;
use Exception;
use Illuminate\Http\Request;
use Auth;


class LoanCaseController extends Controller
{
    use GeneralTrait, MessageTrait;

    private $module_name = 'Case';
    private $module = 'case';
    private $url = 'case.index';

    public function index(Request $request, $status)
    {
        if ($status == 'all') {
            $case = LoanCase::orderBy('id', 'desc')->orderBy('id', 'desc')->get();
        } elseif ($status == 'open') {
            $case = LoanCase::where('status', 'Open')->orderBy('id', 'desc')->get();
        } elseif ($status == 'in_progress') {
            $case = LoanCase::where('status', 'In Progress')->orderBy('id', 'desc')->get();
        } elseif ($status == 'on_hold') {
            $case = LoanCase::where('status', 'On Hold')->orderBy('id', 'desc')->get();
        } elseif ($status == 'resolved') {
            $case = LoanCase::where('status', 'Resolved')->orderBy('id', 'desc')->get();
        } elseif ($status == 'reassigned') {
            $case = LoanCase::where('status', 'Reassigned')->orderBy('id', 'desc')->get();
        } elseif ($status == 'pending_customer') {
            $case = LoanCase::where('status', 'Pending Customer')->orderBy('id', 'desc')->get();
        } elseif ($status == 'pending_review') {
            $case = LoanCase::where('status', 'Pending Review')->orderBy('id', 'desc')->get();
        } elseif ($status == 'closed') {
            $case = LoanCase::where('status', 'Closed')->orderBy('id', 'desc')->get();
        } elseif ($status == 'escalated') {
            $case = LoanCase::where('status', 'Escalated')->orderBy('id', 'desc')->get();
        } elseif ($status == 'rejected') {
            $case = LoanCase::where('status', 'Rejected')->orderBy('id', 'desc')->get();
        } elseif ($status == 'suspended') {
            $case = LoanCase::where('status', 'Suspended')->orderBy('id', 'desc')->get();
        } elseif ($status == 'under_investigation') {
            $case = LoanCase::where('status', 'Under Investigation')->orderBy('id', 'desc')->get();
        } elseif ($status == 'waiting_for_approval') {
            $case = LoanCase::where('status', 'Waiting for Approval')->orderBy('id', 'desc')->get();
        } elseif ($status == 'canceled') {
            $case = LoanCase::where('status', 'Canceled')->orderBy('id', 'desc')->get();
        } else {
            // Handle other cases or set a default behavior
            // For example, you can return an error or default response
        }


        return view('admin.case.index', compact('case'));
    }
    public function create(Request $request, $loan_application_id)
    {
        $data = $request->all();
        $method = $request->input('method');

        $request->validate(
            [
                'case_number' => 'required',
                'topic' => 'required',
                'priority' => 'required',
                'status' => 'required',
            ]
        );

        $newCase = new LoanCase();
        $newCase->case_number = $request->input('case_number');
        $newCase->topic = $request->input('topic');
        $newCase->priority = $request->input('priority');
        $newCase->description = $request->input('description');
        $newCase->status = $request->input('status');
        $newCase->loan_application_id = $loan_application_id;
        $newCase->method = $method;
        $newCase->created_by = Auth::id();
        $newCase->next_follow_up = $request->input('next_follow_up');
        $newCase->save();

        if ($newCase) {
            $assign = new CaseAssignmentHistory();
            $assign->case_id = $newCase->id;
            $assign->admin_user_id = $request->input('assigned_to');
            $assign->save();

            //Send Database Notification
            $notification['icon'] = "ti-briefcase";
            $notification['color'] = "warning";
            $notification['heading'] = $newCase->case_number;
            $notification['msg'] = "New case has been assigned";
            $notification['url'] = route('case.view', ['id' => $newCase->id]);

            $this->sendNotification($assign->admin_user_id, $notification);
        }


        if ($method == 'all') {
            $methods = array('mail','sms', 'inapp');

            foreach ($methods as $m) {
                $this->sendNoticationtoCustomer($newCase, $m);
            }

        } else {
            $this->sendNoticationtoCustomer($newCase, $method);
        }

        return redirect()->back()->with('success', 'Case added successfully.');

    }

    public function sendNoticationtoCustomer($case, $method)
    {
        $loan = LoanApplication::find($case->loan_application_id);

        //send email notification
        $data = array(
            'user_id' => $loan->user_id,
            'template' => 'follow-up',
            'type' => $method,
            'content' => $case->description,
            'loan_application_id' => $loan->id,
        );

        $this->storeMsg($data);
    }

    public function view($id)
    {
        $data['breadcrumb'] = $this->breadcrumb('Loan Case ', 'Detail', $this->url, null);
        $data['loanCase'] = LoanCase::find($id);
        $data['assignmentHistories'] = $data['loanCase']->assignmentHistories->first();
        $data['followupHistories'] = $data['loanCase']->followupHistories;
        $data['loan_application'] = $data['loanCase']->loanApplication;
        $data['applicant'] = $data['loan_application']->user;

        return view('admin.case.show', $data);

    }

    public function update(Request $request, $id)
    {
        try {
            $case = LoanCase::find($id);
            $case->priority = $request->input('priority');
            $case->status = $request->input('status');
            $case->next_follow_up = $request->input('next_follow_up');
            $case->save();

            //re assign case
            $assigned_to = $case->assignmentHistories->first();
            $previous_case_manager = $assigned_to->admin_user_id;

            $assigned_to->admin_user_id = $request->input('assigned_to');
            $assigned_to->save();

            if ($previous_case_manager !== $assigned_to->admin_user_id) {

                $notification['icon'] = "ti-briefcase";
                $notification['color'] = "warning";
                $notification['heading'] = $case->case_number;
                $notification['msg'] = "Case has been updated and has been assigned to you";
                $notification['url'] = route('case.view', ['id' => $case->id]);

                $this->sendNotification($assigned_to->admin_user_id, $notification);
            }

            //Case comments
            $followup = new CaseFollowUpsHistory;
            $followup->case_id = $id;
            $followup->follow_up_date = now();
            $followup->follow_up_by = Auth::id();
            $followup->notes = $request->input('notes');
            $followup->methods = $request->input('methods');
            $followup->save();

            //Send Database Notification
            if ($case->created_by !== Auth::id()) {
                $notification['icon'] = "ti-briefcase";
                $notification['color'] = "warning";
                $notification['heading'] = $case->case_number;
                $notification['msg'] = "Case has been updated";
                $notification['url'] = route('case.view', ['id' => $case->id]);

                $this->sendNotification($case->created_by, $notification);
            }


            return redirect()->back()->with('success', 'Case updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }


    }


}
