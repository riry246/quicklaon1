<?php

namespace App\Http\Controllers\Admin;

use App\Models\MailTemplate;
use Illuminate\Support\Str;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Traits\FormBuilderTrait;
use App\Traits\TableBuilderTrait;
use App\Http\Controllers\Controller;

class EmailTemplateController extends Controller
{
    use GeneralTrait, TableBuilderTrait, FormBuilderTrait;

    private $module_name = 'Email Template Management';
    private $title = 'Email Tempalte';
    private $module = 'email.template';
    private $url = 'email.template.index';


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
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Email Template list', $this->url, null);

        //Get Email Template data
        $data['list'] = MailTemplate::select($data['tabel_fields'])->get();

        return view('admin.general.list', $data);
    }

    public function create()
    {
        //Page Title
        $data['pageTitle'] = $this->pageTitle($this->title, $this->module_name);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Email Template', $this->url, null);

        //Form Builder
        $data['form'] = $this->emailTemplate('email.template.store', null);

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
        $emailTemplate = new MailTemplate();
        $emailTemplate->name = $data['name'];
        $emailTemplate->slug = Str::slug($data['name']);
        $emailTemplate->subject = $data['subject'];
        $emailTemplate->body = $data['body'];
        $emailTemplate->save();

        return redirect()->route('email.template.index')->with('success', 'Email Template "' . $data['name'] . '" created successfully!');
    }

    public function edit($id)
    {
        $emailTemplate = MailTemplate::findOrFail($id);

        //Page Title
        $data['pageTitle'] = $this->pageTitle($this->title, $this->module_name);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Email Template Edit', $this->url, null);

        //Form Builder
        $data['form'] = $this->emailTemplate('email.template.update', $emailTemplate);

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
        $emailTemplate = MailTemplate::findOrFail($id);
        $emailTemplate->name = $data['name'];
        $emailTemplate->slug = Str::slug($data['name']);
        $emailTemplate->subject = $data['subject'];
        $emailTemplate->body = $data['body'];
        $emailTemplate->save();

        return redirect()->route('email.template.index')->with('success', 'Email Template "' . $data['name'] . '" updated successfully!');
    }

    public function destroy($id)
    {
        $emailTemplate = MailTemplate::findOrFail($id);

        if ($emailTemplate) {
            $emailTemplate->delete();
            return back()->with('success', 'Email Template deleted successfully!');
        }
    }
}
