<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanQuestion;
use App\Models\LoanReason;
use App\Models\LoanTerm;
use App\Traits\FormBuilderTrait;
use App\Traits\GeneralTrait;
use App\Traits\TableBuilderTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoanReasonController extends Controller
{
    use GeneralTrait, TableBuilderTrait, FormBuilderTrait;

    private $module_name = 'Reason';
    private $module = 'reason';
    private $url = 'reason.index';


    public function index(Request $request)
    {
        //managing Table data
        $data['tabel_fields'] = array(
            'id',
            'name',
            'image',
            'status',
            'created_at'
        );

        //Button
        $data['btn'] = $this->defaultButton($this->module);

        //Manage Action Button
        $data['action_btn'] = $this->actionButton($this->module);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Reason list', $this->url, null);

        //Get Question data
        $data['list'] = LoanReason::select($data['tabel_fields'])->get();

        return view('admin.general.list', $data);
    }

    public function create()
    {
        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Create Terms & conditions', $this->url, null);

        //Form Builder
        $data['form'] = $this->reasonForm('reason.store', null);

        return view('admin.general.form', $data);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        //Validation
        $request->validate(
            [
                'name' => 'required|max:255',
                'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                // Define validation rules for the image file.
            ],
            [
                'name.required' => 'Name field is required',
                'file.required' => 'Image field is required'

            ]
        );
        $reason = new LoanReason();
        $reason->name = $data['name'];

        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $imageName); // Store the image in the storage folder.
        }

        $reason->image = $imageName;
        $reason->status = $this->checkEnabledStatus($data);
        $reason->save();

        return redirect()->route('reason.index')->with('success', 'Reason "' . $data['name'] . '" created successfully!');
    }

    public function edit($id)
    {
        $group = LoanReason::findOrFail($id);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Reason Edit', $this->url, null);

        //Form Builder
        $data['form'] = $this->reasonForm('reason.update', $group);


        return view('admin.general.form', $data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $request->validate(
            [
                'name' => 'required|max:255',
            ],
            [
                'name.required' => 'Name field is required',
            ]
        );

        $reason = LoanReason::findOrFail($id);
        $reason->name = $data['name'];

        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $imageName); // Store the image in the storage folder.
            $reason->image = $imageName;
        }


        $reason->status = $this->checkEnabledStatus($data);
        $reason->save();

        return redirect()->route('reason.index')->with('success', 'Reason "' . $data['name'] . '" updated successfully!');
    }

    public function destroy($id)
    {
        $reason = LoanReason::findOrFail($id);

        if (!$reason) {
            return redirect()->route('reason.index')->with('error', 'Something went wrong');
        }

        $reason->delete();

        return redirect()->route('reason.index')->with('success', 'Reason "' . $reason->name . '" deleted successfully!');
    }

}