<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Traits\FormBuilderTrait;
use App\Traits\GeneralTrait;
use App\Traits\TableBuilderTrait;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    private $module_name = 'Module Management';
    private $title = 'Modules';
    private $module = 'module';
    private $url = 'module.index';

    use GeneralTrait, TableBuilderTrait, FormBuilderTrait;

    public function __construct()
    {

    }

    public function index()
    {
        $modules = Module::get();

        $data['tabel_fields'] = array(
            'id',
            'name',
            'description'
        );
        //Page Title
        $data['pageTitle'] = $this->pageTitle($this->title,$this->module_name);

        //Button
        $data['btn'] = $this->defaultButton($this->module);

        //Manage Action Button
        $data['action_btn'] = $this->actionButton($this->module);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Module list', $this->url, null);

        $data['list'] = Module::select($data['tabel_fields'])->orderby('id', 'desc')->get();
        //dd($data);

        return view('admin.general.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Page Title
        $data['pageTitle'] = $this->pageTitle($this->title,$this->module_name);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Module list', $this->url, null);

        //Form Builder
        $data['form'] = $this->moduleForm('module.store', null);

        return view('admin.general.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        //Validation
        $request->validate(
            [
                'name' => 'required|max:255',
                'description' => 'required'
            ],
            [
                'name.required' => 'Name field is required',
                'description.required' => 'Description field is required'

            ]
        );
        $module = new Module();
        $module->name = $data['name'];
        $module->description = $data['description'];
        $module->save();

        return redirect()->route('module.index')->with('success_message', 'Module "' . $data['name'] . '" created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $module = Module::findOrFail($id);

        //Page Title
        $data['pageTitle'] = $this->pageTitle($this->title,$this->module_name);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Group Edit', $this->url, null);

        //Form Builder
        $data['form'] = $this->moduleForm('module.update', $module);


        return view('admin.general.form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();

        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required'
        ], [
            'name.required' => 'Name field is required',
            'description.required' => 'Description field is required'

        ]);
        $module = Module::findOrFail($id);
        $module->name = $data['name'];
        $module->description = $data['description'];
        $module->save();

        return redirect()->route('module.index')->with('success_message', 'Module "' . $data['name'] . '" updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
