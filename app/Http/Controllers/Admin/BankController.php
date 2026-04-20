<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\LoanQuestion;
use App\Traits\FormBuilderTrait;
use App\Traits\GeneralTrait;
use App\Traits\TableBuilderTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class BankController extends Controller
{
    use GeneralTrait, TableBuilderTrait, FormBuilderTrait;

    private $module_name = 'Bank List';
    private $module = 'bank';
    private $url = 'bank.index';



    public function update_bak()
    {
        $url = 'https://fundo.com.au/list_bank'; // Replace with your JSON API URL

        $response = Http::get($url);

        if ($response->successful()) {
            $data = $response->json();

            // Insert the fetched data into the "banks" table
            foreach ($data['data'] as $bank) {
                // dd($bank);
                Bank::create([
                    'name' => $bank['name'],
                    'public_code' => $bank['public_code'],
                    'basiq_code' => $bank['basiq_code'],
                    'illion_with_mfa' => $bank['illion_with_mfa'],
                    'short_name' => $bank['short_name'],
                    'full_name' => $bank['full_name'],
                    'bank_tier' => $bank['bank_tier'],
                    'crm_name' => $bank['crm_name'],
                    'username' => $bank['username'],
                    'logo' => $bank['logo'],
                    'password' => $bank['password'],
                    'secret' => $bank['secret'],
                    'basiq_stage' => $bank['basiq_stage'],
                    'basiq_status' => $bank['basiq_status'],
                    'status' => $bank['status'],
                    // Add other columns as needed
                ]);
            }

            return 'Bank data fetched and inserted successfully.';
        } else {
            return 'Failed to fetch bank data from the URL.';
        }
    }

    public function index(Request $request)
    {
        //managing Table data
        $data['tabel_fields'] = array(
            'id',
            'name',
            'code',
            'logo',
            'status',
            'created_at'
        );

        //Button
        $data['btn'] = $this->defaultButton($this->module);

        //Manage Action Button
        $data['action_btn'] = $this->actionButton($this->module);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Question list', $this->url, null);

        //Get Question data
        $data['list'] = Bank::select($data['tabel_fields'])->get();

        return view('admin.general.list', $data);
    }

    public function create()
    {
        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Create Question', $this->url, null);

        //Form Builder
        $data['form'] = $this->questionForm('question.store', null);

        return view('admin.general.form', $data);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        //Validation
        $request->validate(
            [
                'questions' => 'required|max:255',
                'description' => 'required'
            ],
            [
                'questions.required' => 'Question field is required',
                'description.required' => 'Description field is required'

            ]
        );
        $question = new LoanQuestion();
        $question->questions = $data['questions'];
        $question->slug = Str::slug($data['questions']);
        $question->description = $data['description'];
        $question->status = $this->checkEnabledStatus($data);
        $question->save();

        return redirect()->route('question.index')->with('success', 'Question "' . $data['questions'] . '" created successfully!');
    }

    public function edit($id)
    {
        $group = LoanQuestion::findOrFail($id);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Question Edit', $this->url, null);

        //Form Builder
        $data['form'] = $this->questionForm('question.update', $group);


        return view('admin.general.form', $data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $request->validate(
            [
                'questions' => 'required|max:255',
                'description' => 'required'
            ],
            [
                'questions.required' => 'Question field is required',
                'description.required' => 'Description field is required'

            ]
        );

        $question = LoanQuestion::findOrFail($id);
        $question->questions = $data['questions'];
        $question->slug = Str::slug($data['questions']);
        $question->description = $data['description'];
        $question->status = $this->checkEnabledStatus($data);
        $question->save();

        return redirect()->route('question.index')->with('success', 'Question "' . $data['questions'] . '" updated successfully!');
    }

    public function changePrimaryAccount(Request $request)
    {
        try {
            $bank_account_id = $request->input('bank_account_id');
            $primary_account = $request->input('primary_account');
            $onlyfromPrimary = $request->input('onlyfromPrimary') ? 1 : 0;

            $bank = BankAccount::findOrFail($bank_account_id); // Use findOrFail to throw an exception if the record is not found
            $bank->primary_account = $primary_account;
            $bank->onlyfromPrimary = $onlyfromPrimary;
            $bank->save();

            return redirect()->back()->with('success', 'Primary Bank Account updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating Primary Bank Account: ' . $e->getMessage());
        }
    }
}