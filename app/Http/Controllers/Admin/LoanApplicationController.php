<?php

namespace App\Http\Controllers\Admin;

use App\Models\BankAccount;
use App\Models\CashfasterScore;
use App\Models\IdVerification;
use App\Models\IllionServiceAbility;
use App\Models\LoanStatement;
use App\Models\UserIdVerification;
use App\Traits\IllionTrait;
use App\Traits\LoanTrait;
use App\Traits\MessageTrait;
use Illuminate\Support\Str;
use App\Models\Factor;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Models\LoanApplication;
use App\Traits\FormBuilderTrait;
use App\Traits\TableBuilderTrait;
use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\DocumentType;
use App\Models\User;
use App\Models\UserAttr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class LoanApplicationController extends Controller
{
    use GeneralTrait, TableBuilderTrait, FormBuilderTrait, LoanTrait, MessageTrait, IllionTrait;

    private $module_name = 'Loan Application';
    private $module = 'loan';
    private $url = 'loan.index';

    public function index(Request $request, $status)
    {
        $data['breadcrumb'] = $this->breadcrumb($this->module_name . ' ( ' . ucfirst($status) . ' )', 'Application', $this->url, null);

        $query = LoanApplication::select('*', 'loan_applications.id as id', 'loan_applications.status as status')
            ->join('users', 'loan_applications.user_id', '=', 'users.id')
            ->whereNotNull('users.first_name');

        if ($status === 'in_default') {
            $query->where('loan_applications.in_default', 1);
        } elseif ($status === 'is_bad_debt') {
            $query->where('loan_applications.is_bad_debt', 1);
        } elseif ($status !== 'all') {
            $query->where('loan_applications.status', $status);
        } elseif ($status == 'all') {
            $query->whereNotIn('loan_applications.status', ['Declined']);
        }

        $group = $this->getAdminGroup();
        if ($group == 'credit-assessor') {
            $query->where('loan_applications.assign_to', Auth::id());
        }

        $data['loans'] = $query->orderBy('loan_applications.id', 'desc')->get();

        return view('admin.loans.index', $data);
    }


    public function userWiseLoanApplicaiton(Request $request, $id)
    {
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, $this->getUserName($id), $this->url, null);
        $data['loans'] = LoanApplication::where('user_id', $id)->orderby('id', 'desc')->get();



        return view('admin.loans.index', $data);
    }

    public function show($id)
    {

        $data['documentType'] = DocumentType::get();

        $loanApplication = LoanApplication::findOrFail($id);

        $currentUser = Auth::user();

        if ($loanApplication->viewed_by_user_id && $loanApplication->viewed_by_user_id != $currentUser->id) {
            $viewingUser = $loanApplication->viewedByUser;
            //return redirect()->back()->with('error', 'This loan application #'.$loanApplication->id .' is currently being viewed by '. $this->getUserName($loanApplication->viewed_by_user_id));
        } else {
            $loanApplication->viewed_by_user_id = $currentUser->id;
            $loanApplication->save();
        }

        //Loan Application
        $data['currentUser'] = $currentUser;
        $data['document'] = $loanApplication->documents;
        $data['loanFollowUps'] = $loanApplication->loanFollowUps;
        $data['loanLatestFollowUps'] = $loanApplication->loanLatestFollowUps;
        $data['creditScore'] = $loanApplication->creditScore;
        $data['latestcreditScore'] = $loanApplication->latestcreditScore;
        $data['user'] = $loanApplication->user;
        $data['noofloans'] = $loanApplication->user->countLoans;
        $data['noofdishournedpayment'] = $loanApplication->dishournedStatement;
        $data['loanstatement'] = $loanApplication->statement;
        $data['contract'] = $loanApplication->contractList;
        $data['idverification'] = $loanApplication->user->idVerifcation;
        $data['bank'] = $this->getActiveBank($loanApplication->id);
        $data['accountList'] = $this->getAccountListofActiveBank($data['bank']);
        $data['score'] = $data['user']->cashfasterScores->sum('score');
        $userAttrs = UserAttr::where('application_id', $loanApplication->id)->where('user_id', $loanApplication->user_id)->get();


        //Illion Data
        $data['illionCustomerInfo'] = $loanApplication->illionCustomerInfo;
        $data['illionBankAccount'] = $loanApplication->illionBankAccount;
        $data['illionPrimaryAccount'] = $loanApplication->illionPrimaryAccount;
        $data['riskScore'] = $loanApplication->riskScore;

        if ($data['illionBankAccount']) {
            $data['affordabilty'] = $this->generateConsumerAffordability($id);
        }

        $data['illionServiceAbility'] = $loanApplication->illionServiceAbility()->first();
      //  dd(json_decode($data['illionServiceAbility']->loans));
        $data['sms'] = $loanApplication->sms;
        $data['email'] = $loanApplication->email;
        $data['inapp'] = $loanApplication->inapp;
        $data['leadmarket'] = $loanApplication->leadMarket;

        $data['loanServiceAbility'] = $loanApplication->loanServiceAbility()->first();
       

        if ($data['loanServiceAbility']) {
            $data['statements'] = json_decode($data['loanServiceAbility']->report);

            $data['summary'] = [];
            $data['loans'] = [];

            //Get total factor wise
            $factor = Factor::all();
            foreach ($factor as $f) {
                $type = $f->type;
                $factor = $f->value;
                if ($type == 'expenses') {
                    $data['summary'][$type]['summary']['total'] = 0;
                    foreach ($data['statements']->$type as $key => $value) {
                        foreach ($value as $k => $e) {
                            if (isset($e->analysis)) {
                                $data['summary'][$type][$e->title]['total'] = $e->analysis->amount->total;
                                $data['summary'][$type][$e->title]['frequency'] = $e->analysis->frequency->display;
                                $data['summary'][$type]['summary']['total'] += $e->analysis->amount->total;

                            }
                            if ($key == 'Liabilities') {
                                $data['loans'][$e->title] = $e;
                            }
                        }
                    }

                } else {
                    $data['summary'][$type]['summary']['total'] = 0;
                    foreach ($data['statements']->$type as $s) {
                        if (isset($s->analysis)) {
                            $data['summary'][$type][$s->title]['total'] = $s->analysis->amount->total;
                            $data['summary'][$type][$s->title]['frequency'] = $s->analysis->frequency->display;
                            $data['summary'][$type]['summary']['total'] += $s->analysis->amount->total;
                        }
                    }
                }
            }

            $data['noOfLoans'] = 0;
            $data['loanRepayments'] = 0;
            foreach ($data['loans'] as $k => $v) {
                if ($v->analysis->amount->total !== '0.00') {
                    foreach ($v->subgroup as $sub) {

                        if ($sub->analysis->frequency->next->date) {
                            $data['noOfLoans'] += 1;
                            if ($sub->analysis->frequency->display == "Weekly") {
                                $data['loanRepayments'] += $sub->analysis->frequency->next->amount * 4;
                            } else if ($sub->analysis->frequency->display == "Fortnightly") {
                                $data['loanRepayments'] += $sub->analysis->frequency->next->amount * 2;
                            } else {
                                $data['loanRepayments'] += $sub->analysis->frequency->next->amount;
                            }


                        }

                    }
                }


            }
        }

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb('Loan Detail', 'SACC Application ID# ' . $id . ' ' . $data['user']->first_name . ' ' . $data['user']->last_name, $this->url, null);

        if ($userAttrs) {
            $userAttrArray = [];

            foreach ($userAttrs as $attr) {
                $attrName = $attr['attr'];
                $attrValue = $attr['value'];



                if (strpos($attrName, 'expenses_') === 0) {

                    $userAttrArray['expenses'][$attrName] = $attrValue;
                } elseif (strpos($attrName, 'question_') === 0) {

                    $userAttrArray['question'][$attrName] = $attrValue;
                } else {
                    $userAttrArray[$attrName] = $attrValue;
                }
            }
            $result = $userAttrArray;

        }
        $data['result'] = $result;
        $data['loanapplication'] = $loanApplication;


        //Generate temp login token
        $user = User::find($loanApplication->user_id);
        if (!$user->temp_login_token) {
            $user->temp_login_token = $this->generateUniqueUserRefNumber($loanApplication->user_id);
            $user->save();
        }




        $this->logActivity('Viewed a application: #' . $id, 'Loan Detail of ' . $data['user']->first_name);


        return view('admin.loans.detail', $data);
    }

    public function clearViewStatus($id)
    {
        $loanapplication = LoanApplication::find($id);


        $loanapplication->viewed_by_user_id = null;
        $loanapplication->save();


        return response()->json(['status' => 'success']);
    }
    public function sendAuthCode()
    {
        $this->sendAuthCodetoAdmin();

        return response()->json(['success' => 'Authorization Code has been sent to your registered mobile number'], 200);
    }

    public function send2FAauthCode()
    {

        $mobiles = array('0499936355', '0415166323');

        foreach ($mobiles as $mobile) {
            $this->sendAuthCodetoAdminNumberWise($mobile);
        }



        return response()->json(['success' => 'Authorization Code has been sent to your registered mobile number'], 200);
    }

    function changeLoanStatus($app_id, $status)
    {
        if ($status == 'completed') {
            $this->settleStatement($app_id);
        }

        $application = LoanApplication::findorfail($app_id);
        $application->status = $status;
        $application->reviewed_by = Auth::id();
        $application->reviewed_date = now();
        $application->save();

        $user = User::findOrFail($application->user_id);

        //Send Database Notification
        $notification['icon'] = "ti-file-symlink";
        $notification['color'] = "warning";
        $notification['heading'] = "Application " . ucfirst($status);
        $notification['msg'] = "Your recent application is on " . $status . ' stage.';

        $this->sendNotification($application->user_id, $notification);


        return redirect()->back()->with('success', 'Application status changed to ' . $status . '.');
    }

    function sendEmailNotification($user_id, $loan_id, $template)
    {

        $data = array(
            'user_id' => $user_id,
            'template' => $template,
            'type' => 'mail',
            'loan_application_id' => $loan_id,
        );


        $this->storeMsg($data);


        //send Inapp Notification
        $datas = array(
            'user_id' => $user_id,
            'subject' => 'Application Declined',
            'content' => "We regret to inform you that your loan application has been declined.",
            'type' => 'inapp',
            'loan_application_id' => $loan_id,
        );

        $this->storeMsg($datas);

        return;
    }

    public function preApproval(Request $request, $id)
    {
        // Validate the request
        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:1',
            'frequency' => 'required|string',
            'duration' => 'required|integer|min:1',
            'first_repayment_date' => 'required|date|after:today',
        ]);

        $loanapplication = LoanApplication::find($id);
        if (!$loanapplication) {
            return redirect()->back()->with('error', 'Loan application not found.');
        }

        $user = $loanapplication->user;
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $loanapplication->approved_amount = $validatedData['amount'];
        $loanapplication->customer_confirmation = null;
        $loanapplication->frequency = $validatedData['frequency'];
        $loanapplication->duration = $validatedData['duration'];
        $loanapplication->first_repayment_date = $validatedData['first_repayment_date'];
        $loanapplication->reviewed_date = now();
        $loanapplication->reviewed_by = Auth::id();
        $loanapplication->save();

        // Send Email Notification
        $datas = [
            'user_id' => $user->id,
            'template' => 'pre-approval-of-loan',
            'type' => 'mail',
            'amount' => $loanapplication->approved_amount,
            'loan_application_id' => $id,
        ];
        $this->storeMsg($datas);

        $datas = [
            'user_id' => $user->id,
            'subject' => 'Complete application',
            'content' => 'Great news! You have been pre-approved for $' . $loanapplication->approved_amount . ' a loan with us.',
            'type' => 'inapp',
            'loan_application_id' => $id,
        ];

        $this->storeMsg($datas);

        // Send Database Notification
        $notification = [
            'icon' => 'ti-mail',
            'color' => 'warning',
            'heading' => 'You have been pre-approved for a loan with CashFaster.',
            'msg' => 'Confirming the amount we are prepared to lend to you at this stage is $' . $loanapplication->approved_amount,
        ];

        $this->sendNotification($loanapplication->user_id, $notification);

        $this->logActivity('Pre Approval of Application ID #' . $id, null);

        return redirect()->back()->with('success', 'Pre-approval mail has been sent.');
    }

    function assignTo(Request $request, $id)
    {
        $assigned_to = $request->input('assigned_to');

        $loanApplication = LoanApplication::find($id);
        $loanApplication->assign_to = $assigned_to;
        $loanApplication->save();

        return redirect()->back()->with('success', 'Loan application has been successfully assigned');

    }
}