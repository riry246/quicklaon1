<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanQuestion;
use App\Models\LoanTerm;
use App\Traits\FormBuilderTrait;
use App\Traits\GeneralTrait;
use App\Traits\TableBuilderTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoanTermController extends Controller
{
    use GeneralTrait, TableBuilderTrait, FormBuilderTrait;

    private $module_name = 'Terms & Conditions';
    private $module = 'term';
    private $url = 'term.index';


    public function index(Request $request)
    {
        //managing Table data
        $data['tabel_fields'] = array(
            'id',
            'name',
            'status',
            'created_at'
        );

        //Button
        $data['btn'] = $this->defaultButton($this->module);

        //Manage Action Button
        $data['action_btn'] = $this->actionButton($this->module);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Terms and Conditions list', $this->url, null);

        //Get Question data
        $data['list'] = LoanTerm::select($data['tabel_fields'])->get();

        return view('admin.general.list', $data);
    }

    public function create()
    {
        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Create Terms & conditions', $this->url, null);

        //Form Builder
        $data['form'] = $this->termsForm('term.store', null);

        return view('admin.general.form', $data);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        //Validation
        $request->validate(
            [
                'name' => 'required|max:255',
                'terms' => 'required'
            ],
            [
                'name.required' => 'Name field is required',
                'terms.required' => 'Terms field is required'

            ]
        );
        $terms = new LoanTerm();
        $terms->name = $data['name'];
        $terms->slug = Str::slug($data['name']);
        $terms->terms = $data['terms'];
        $terms->status = $this->checkEnabledStatus($data);
        $terms->save();

        return redirect()->route('term.index')->with('success', 'Terms and Conditions "' . $data['name'] . '" created successfully!');
    }

    public function edit($id)
    {
        $group = LoanTerm::findOrFail($id);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Terms and Conditions Edit', $this->url, null);

        //Form Builder
        $data['form'] = $this->termsForm('term.update', $group);


        return view('admin.general.form', $data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $request->validate(
            [
                'name' => 'required|max:255',
                'terms' => 'required'
            ],
            [
                'name.required' => 'Name field is required',
                'terms.required' => 'Terms field is required'

            ]
        );

        $terms = LoanTerm::findOrFail($id);
        $terms->name = $data['name'];
        $terms->slug = Str::slug($data['name']);
        $terms->terms = $data['terms'];
        $terms->status = $this->checkEnabledStatus($data);
        $terms->save();

        return redirect()->route('term.index')->with('success', 'Terms and Conditions "' . $data['name'] . '" updated successfully!');
    }

    public function destroy($id)
    {
        $term = LoanTerm::findOrFail($id);

        if (!$term) {
            return redirect()->route('term.index')->with('error', 'Something went wrong');
        }

        $term->delete();

        return redirect()->route('term.index')->with('success', '"' . $term->name . '" deleted successfully!');
    }
}