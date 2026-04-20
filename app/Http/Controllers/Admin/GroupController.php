<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Module;
use App\Models\ModuleAction;
use App\Models\Permission;
use App\Traits\FormBuilderTrait;
use App\Traits\GeneralTrait;
use App\Traits\TableBuilderTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    use GeneralTrait, TableBuilderTrait, FormBuilderTrait;

    private $module_name = 'Group Management';
    private $title = 'Groups';
    private $module = 'group';
    private $url = 'group.index';


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
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Group', $this->url, null);

        //Get Group data
        $data['list'] = Group::select($data['tabel_fields'])->get();

        return view('admin.general.list', $data);
    }

    public function create()
    {
        $modules = Module::get();
        $moduleActions = ModuleAction::get();

        //Page Title
        $data['pageTitle'] = $this->pageTitle($this->title, $this->module_name);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Group list', $this->url, null);

        //Form Builder
        $data['form'] = $this->groupForm('group.store', null);

        return view('admin.groups.create', $data, compact('modules', 'moduleActions'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $permissionData = $request->permissions;

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
        $group = new Group();
        $group->name = $data['name'];
        $group->slug = Str::slug($data['name']);
        $group->description = $data['description'];
        $group->save();

        if ($group) {
            if ($permissionData != null) {
                foreach ($permissionData as $per) {
                    $permission = new Permission();
                    $permission->module_action_id = $per;
                    $permission->group_id = $group->id;
                    $permission->granted_by = Auth::user()->id;
                    $permission->granted_at = Carbon::now()->toDateTimeString();
                    $permission->save();
                }
            }
        }

        return redirect()->route('group.index')->with('success_message', 'Group "' . $data['name'] . '" created successfully!');
    }

    public function edit($id)
    {
        $group = Group::findOrFail($id);
        $permissions = Permission::where('group_id', $group->id)->get();

        //Page Title
        $data['pageTitle'] = $this->pageTitle($this->title, $this->module_name);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Group Edit', $this->url, null);

        //Form Builder
        $data['form'] = $this->groupForm('group.update', $group);


        return view('admin.groups.edit', $data, compact('group', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $permissionData = $request->permissions;


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
        $group = Group::findOrFail($id);
        $group->name = $data['name'];
        $group->slug = Str::slug($data['name']);
        $group->description = $data['description'];
        $group->save();


        if ($group) {
            $oldPermissions = Permission::where('group_id', $group->id)->get();

            if (count($oldPermissions) > 0) {
                foreach ($oldPermissions as $oldPermission) {
                    $oldPermission->delete();
                }
            }

            if ($permissionData != null) {
                foreach ($permissionData as $per) {
                    $permission = new Permission();
                    $permission->module_action_id = $per;
                    $permission->group_id = $group->id;
                    $permission->granted_by = Auth::user()->id;
                    $permission->granted_at = Carbon::now()->toDateTimeString();
                    $permission->save();
                }
            }
        }

        return redirect()->route('group.index')->with('success_message', 'Group "' . $data['name'] . '" updated successfully!');
    }

    public function destroy($id)
    {
        $group = Group::findOrFail($id);

        if ($group) {
            $group->delete();
            return back()->with('success_message', 'Group deleted successfully!');
        }
    }
}
