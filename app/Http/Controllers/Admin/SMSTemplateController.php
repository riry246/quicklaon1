<?php

namespace App\Http\Controllers\Admin;

use App\Models\SMSTemplate;
use Illuminate\Support\Str;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Traits\FormBuilderTrait;
use App\Traits\TableBuilderTrait;
use App\Http\Controllers\Controller;

class SMSTemplateController extends Controller
{
    use GeneralTrait, TableBuilderTrait, FormBuilderTrait;

    private $module_name = 'SMS Template Management';
    private $title = 'SMS Tempalte';
    private $module = 'sms.template';
    private $url = 'sms.template.index';


    public function index(Request $request)
    {
        //managing Table data
        $data['tabel_fields'] = array(
            'id',
            'name',
            'slug',
            'subject',
        );

        //Page Title
        $data['pageTitle'] = $this->pageTitle($this->title, $this->module_name);

        //Button
        $data['btn'] = $this->defaultButton($this->module);

        //Manage Action Button
        $data['action_btn'] = $this->actionEditButton($this->module);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'SMS Template list', $this->url, null);

        //Get SMS Template data
        $data['list'] = SMSTemplate::select($data['tabel_fields'])->get();

        return view('admin.general.list', $data);
    }

    public function create()
    {
        //Page Title
        $data['pageTitle'] = $this->pageTitle($this->title, $this->module_name);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'SMS Template', $this->url, null);

        //Form Builder
        $data['form'] = $this->SMSTemplate('sms.template.store', null);

        return view('admin.general.form', $data);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        //Validation
        $request->validate(
            [
                'name' => 'required|max:255',
                'subject' => 'required|max:255',
                'body' => 'required',
            ],
            [
                'name.required' => 'Name field is required',
                'subject.required' => 'Subject field is required',
                'body.required' => 'Content field is required',
            ]
        );
        $SMSTemplate = new SMSTemplate();
        $SMSTemplate->name = $data['name'];
        $SMSTemplate->slug = Str::slug($data['name']);
        $SMSTemplate->subject = $data['subject'];
        $SMSTemplate->body = $data['body'];
        $SMSTemplate->save();

        return redirect()->route('sms.template.index')->with('success', 'SMS Template "' . $data['name'] . '" created successfully!');
    }

    public function edit($id)
    {
        $SMSTemplate = SMSTemplate::findOrFail($id);

        //Page Title
        $data['pageTitle'] = $this->pageTitle($this->title, $this->module_name);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'SMS Template Edit', $this->url, null);

        //Form Builder
        $data['form'] = $this->SMSTemplate('sms.template.update', $SMSTemplate);

        return view('admin.general.form', $data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        //Validation
        $request->validate(
            [
                'name' => 'required|max:255',
                'subject' => 'required|max:255',
                'body' => 'required',
            ],
            [
                'name.required' => 'Name field is required',
                'subject.required' => 'Subject field is required',
                'body.required' => 'Content field is required',
            ]
        );
        $SMSTemplate = SMSTemplate::findOrFail($id);
        $SMSTemplate->name = $data['name'];
        $SMSTemplate->slug = Str::slug($data['name']);
        $SMSTemplate->subject = $data['subject'];
        $SMSTemplate->body = $data['body'];
        $SMSTemplate->save();

        return redirect()->route('sms.template.index')->with('success', 'SMS Template "' . $data['name'] . '" updated successfully!');
    }

    public function destroy($id)
    {
        $SMSTemplate = SMSTemplate::findOrFail($id);

        if ($SMSTemplate) {
            $SMSTemplate->delete();
            return back()->with('success', 'SMS Template deleted successfully!');
        }
    }
}
