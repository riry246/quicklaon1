<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use App\Models\DocumentType;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Traits\FormBuilderTrait;
use App\Traits\TableBuilderTrait;
use App\Http\Controllers\Controller;

class DocumentTypeController extends Controller
{
    use GeneralTrait, TableBuilderTrait, FormBuilderTrait;

    private $module_name = 'Document Type Management';
    private $title = 'Document Types';
    private $module = 'document_type';
    private $url = 'document_type.index';


    public function index(Request $request)
    {
        //managing Table data
        $data['tabel_fields'] = array(
            'id',
            'name',
            'slug',
        );

        //Page Title
        $data['pageTitle'] = $this->pageTitle($this->title, $this->module_name);

        //Button
        $data['btn'] = $this->defaultButton($this->module);

        //Manage Action Button
        $data['action_btn'] = $this->actionButton($this->module);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Document Type list', $this->url, null);

        //Get Document Type data
        $data['list'] = DocumentType::select($data['tabel_fields'])->get();

        return view('admin.general.list', $data);
    }

    public function create()
    {
        //Page Title
        $data['pageTitle'] = $this->pageTitle($this->title, $this->module_name);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Document Type list', $this->url, null);

        //Form Builder
        $data['form'] = $this->documentType('document_type.store', null);

        return view('admin.general.form', $data);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        //Validation
        $request->validate(
            [
                'name' => 'required|max:255',
            ],
            [
                'name.required' => 'Name field is required',
            ]
        );
        $documentType = new DocumentType();
        $documentType->name = $data['name'];
        $documentType->slug = Str::slug($data['name']);
        $documentType->save();

        return redirect()->route('document_type.index')->with('success', 'Document Type "' . $data['name'] . '" created successfully!');
    }

    public function edit($id)
    {
        $documentType = DocumentType::findOrFail($id);

        //Page Title
        $data['pageTitle'] = $this->pageTitle($this->title, $this->module_name);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Document Type Edit', $this->url, null);

        //Form Builder
        $data['form'] = $this->documentType('document_type.update', $documentType);

        return view('admin.general.form', $data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        //Validation
        $request->validate(
            [
                'name' => 'required|max:255',
            ],
            [
                'name.required' => 'Name field is required',
            ]
        );
        $documentType = DocumentType::findOrFail($id);
        $documentType->name = $data['name'];
        $documentType->slug = Str::slug($data['name']);
        $documentType->save();

        return redirect()->route('document_type.index')->with('success', 'Document Type "' . $data['name'] . '" updated successfully!');
    }

    public function destroy($id)
    {
        $documentType = DocumentType::findOrFail($id);

        if ($documentType) {
            $documentType->delete();
            return back()->with('success', 'Document Type deleted successfully!');
        }
    }
}
