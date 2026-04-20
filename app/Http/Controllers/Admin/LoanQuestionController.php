<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanQuestion;
use App\Traits\FormBuilderTrait;
use App\Traits\GeneralTrait;
use App\Traits\TableBuilderTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoanQuestionController extends Controller
{
    use GeneralTrait, TableBuilderTrait, FormBuilderTrait;

    private $module_name = 'Loan Question';
    private $module = 'question';
    private $url = 'question.index';


    public function index(Request $request)
    {
        //managing Table data
        $data['tabel_fields'] = array(
            'id',
            'questions',
            'description',
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
        $data['list'] = LoanQuestion::select($data['tabel_fields'])->get();

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
        $question->slug = 'question_'.Str::slug($data['questions']);
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
        $question->slug = 'question_'.Str::slug($data['questions']);
        $question->description = $data['description'];
        $question->status = $this->checkEnabledStatus($data);
        $question->save();

        return redirect()->route('question.index')->with('success', 'Question "' . $data['questions'] . '" updated successfully!');
    }

    public function destroy($id)
    {
        $question = LoanQuestion::findOrFail($id);

        if (!$question) {
            return redirect()->route('question.index')->with('error', 'Something went wrong');
        }

        $question->delete();

        return redirect()->route('question.index')->with('success', 'Question "' . $question->questions . '" deleted successfully!');
    }
}