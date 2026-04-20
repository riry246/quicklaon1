<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupUser;
use App\Traits\FormBuilderTrait;
use App\Traits\GeneralTrait;
use App\Traits\TableBuilderTrait;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FAQRCode\Google2FA;

class UserController extends Controller
{

    private $module_name = 'User Management';
    private $title = 'Users';
    private $module = 'user';
    private $url = 'user.index';


    use GeneralTrait, TableBuilderTrait, FormBuilderTrait;

    public function index()
    {
        $data['tabel_fields'] = array(
            'id',
            'first_name',
            'last_name',
            'email',
            'mobile',
            'group',
            'status',
            'created_at'
        );
        //Page Title
        $data['pageTitle'] = $this->pageTitle($this->title, $this->module_name);

        //Button
        $data['btn'] = $this->defaultButton($this->module);

        //Manage Action Button
        $data['action_btn'] = $this->actionButton($this->module);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Admin', $this->url, null);

        $data['list'] = User::whereNotNull('first_name')
            ->whereDoesntHave('groups', function ($query) {
                $query->where('slug', 'customer');
            })
            ->with('groups')
            ->orderBy('id', 'desc')
            ->get();


        return view('admin.general.list', $data);
    }
    public function customer()
    {
        $data['tabel_fields'] = array(
            'id',
            'first_name',
            'last_name',
            'email',
            'mobile',
            'group',
            'status',
            'created_at'
        );
        //Page Title
        $data['pageTitle'] = $this->pageTitle($this->title, $this->module_name);

        //Button
        $data['btn'] = $this->defaultButton($this->module);

        //Manage Action Button
        $data['action_btn'] = $this->actionButton('customer');

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Customer', $this->url, null);

        $data['list'] = User::whereNotNull('first_name')
            ->whereHas('groups', function ($query) {
                $query->where('slug', 'customer');
            })
            ->with('groups')
            ->orderBy('id', 'desc')
            ->get();


        return view('admin.general.list', $data);
    }

    public function create()
    {
        $data['pageTitle'] = $this->pageTitle($this->title, $this->module_name);
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Add new', $this->url, null);
        $groups = Group::all();

        $data['form'] = $this->userForm('user.store', $groups, $value = null, null,null,null);
        return view('admin.general.form', $data);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        //Validation
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|max:255',
            'password' => 'required|string|min:8',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|min:9',
            'group_id' => 'required',
            'dob' => 'required',
        ], [
            'first_name.required' => 'First name field is required',
            'last_name.required' => 'Last name field is required',
            'password.required' => 'Password field is required',
            'password.min' => 'Password Must be at least 6 characters',
            'email.required' => 'Email field is required',
            'mobile.required' => 'Mobile field is required',
            'group_id.required' => 'Group field is required',
            'dob.required' => 'Date of Birth field is required',
        ]);

        $google2fa = app('pragmarx.google2fa');

        $user = new User();
        $user->first_name = $data['first_name'];
        $user->middle_name = $data['middle_name'];
        $user->last_name = $data['last_name'];
        $user->password = Hash::make($data['password']);
        $user->email = $data['email'];
        $user->mobile = $data['mobile'];
        $user->status = $this->checkEnabledStatus($data);
        $user->dob = $data['dob'];
        $user->save();

        $user->groups()->attach($data['group_id']);

        return redirect()->route('user.edit', $user->id)->with('success', 'User ' . $user->first_name . ' created successfully!');
    }


    public function edit($id)
    {
        $user = User::findOrFail($id);
        $group = Group::get();
        $QR_Image = null;
        $secret = null;

        //Managing Page Title
        $data['pageTitle'] = $this->pageTitle($this->title, $this->module_name);

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'User Edit', $this->url, null);

        $groupId = $user->groups->pluck('id')->first();

        $groupName = Group::find($groupId);

        if ($groupName->slug != 'customer') {
            try {
                $secret = $user->google2fa_secret;
                if ($secret) {
                    $twoFa = new Google2FA();
                    $QR_Image = $twoFa->getQRCodeInline(
                        'Cashfaster',
                        $user->email,
                        $user->google2fa_secret
                    );
                }
            } catch (\Exception $e) {
                $QR_Image = null;
                $secret = null;
            }
        }

        //Form Builder
        $data['form'] = $this->userForm('user.update', $group, $user, $groupId, $QR_Image, $secret);

        return view('admin.general.form', $data);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->all();

        $validation_mobile = 'required';
        $validation_email = 'required|string|email|max:255';

        if ($user->mobile !== $data['mobile']) {
            $validation_mobile = 'required|unique:users';
        }

        if ($user->email !== $data['email']) {
            $validation_email = 'required|string|email|max:255|unique:users';
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|max:255',
            'email' => $validation_email,
            'mobile' => $validation_mobile,
            'group_id' => 'required',
            'dob' => 'required',
        ], [
            'first_name.required' => 'First name field is required',
            'last_name.required' => 'Last name field is required',
            'email.required' => 'Email field is required',
            'mobile.required' => 'Mobile field is required',
            'group_id.required' => 'Group field is required',
            'dob.required' => 'Date of Birth field is required',
        ]);


        $user->first_name = $data['first_name'];
        $user->middle_name = $data['middle_name'];
        $user->last_name = $data['last_name'];
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->email = $data['email'];
        $user->mobile = $data['mobile'];
        $user->dob = $data['dob'];
        $user->status = $this->checkEnabledStatus($data);

        $group = Group::find($data['group_id']);

        if ($group->slug != 'customer') {
            $google2fa = app('pragmarx.google2fa');
            $user->google2fa_secret = $google2fa->generateSecretKey();
        }



        $user->save();

        $groupId = $user->groups->pluck('id')->all();

        $user->groups()->detach($groupId);

        $user->groups()->attach($data['group_id']);

        return redirect()->route('user.index')->with('success_message', 'User "' . $user->first_name . '" updated successfully!');
    }


    public function destroy($id)
    {
        $data = User::find($id);
        $data->delete();

        return redirect()->route($this->url)->with('success', 'User ' . $data['name'] . ' deleted successfully!');
    }
}