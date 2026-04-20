<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\ModuleAction;
use App\Traits\FormBuilderTrait;
use App\Traits\GeneralTrait;
use App\Traits\TableBuilderTrait;
use Illuminate\Http\Request;

class ModuleActionController extends Controller
{
    private $module_name = 'Module Action Management';
    private $title = 'Module Actions';
    private $module = 'module_action';
    private $url = 'module_action.index';

    use GeneralTrait, TableBuilderTrait, FormBuilderTrait;

    public function __construct()
    {
    }

    public function index()
    {
        $module_action = ModuleAction::with('module')->get();

        //Page Title
        $data['pageTitle'] = $this->pageTitle($this->title, $this->module_name);

        //Button
        $data['btn'] = $this->defaultButton($this->module);

        //Manage Action Button
        $data['action_btn'] = $this->actionButton($this->module);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Module list', $this->url, null);

        $modules = Module::orderby('id', 'desc')->get();
        $moduleActions = ModuleAction::orderby('id', 'desc')->get();

        return view('admin.moduleActions.index', $data, compact('modules', 'moduleActions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Page Title
        $data['pageTitle'] = $this->pageTitle($this->title, $this->module_name);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Module list', $this->url, null);

        $module = Module::all();

        $data['form'] = $this->moduleActionForm('module_action.store', $module, $value = null);


        $permissions = ['View' => 'View', 'Create' => 'Create', 'Update' => 'Update', 'Delete' => 'Delete'];

        return view('admin.moduleActions.form', $data, compact('module', 'permissions'));
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
                'module_id' => 'required|max:255',
                'action' => 'required'
            ],
            [
                'module_id.required' => 'Module field is required',
                'action.required' => 'Action field is required'
            ]
        );


        foreach($data['action'] as $a){


        $module_action = new ModuleAction();
        $module_action->module_id = $data['module_id'];
        $module_action->action = $a;

        $exists = ModuleAction::where('module_id', $module_action->module_id)->where('action', $module_action->action)->exists();
        if (!$exists) {
            $module_action->save();
        }

        }           
            return redirect()->route('module_action.index')->with('success_message', 'Module Action  created successfully!');
        
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
        $module = Module::all();
        $module_action = ModuleAction::findOrFail($id);
        $permissions = ['View' => 'View', 'Create' => 'Create', 'Update' => 'Update', 'Delete' => 'Delete'];

        //Page Title
        $data['pageTitle'] = $this->pageTitle($this->title, $this->module_name);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Group Edit', $this->url, null);

        return view('admin.moduleActions.edit', $data, compact('module', 'module_action', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();

        $request->validate([
            'module_id' => 'required|max:255',
            'action' => 'required'
        ], [
            'module_id.required' => 'Module field is required',
            'action.required' => 'Action field is required'

        ]);
        $module_action = ModuleAction::findOrFail($id);
        $module_action->module_id = $data['module_id'];
        $module_action->action = $data['action'];
        $module_action->save();

        return redirect()->route('module_action.index')->with('success_message', 'Module Action "' . $data['action'] . '" updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}