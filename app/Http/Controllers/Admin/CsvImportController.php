<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use App\Traits\FormBuilderTrait;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class CsvImportController extends Controller
{
    use GeneralTrait, FormBuilderTrait;
    private $module_name = 'User Import';
    private $title = 'Users';
    private $module = 'user';
    private $url = 'user.index';

    public function showForm()
    {
        $data['pageTitle'] = $this->pageTitle($this->title, $this->module_name);
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Import Users', $this->url, null);

        $data['form'] = $this->importForm('user.import.file', $value = null);
        return view('admin.general.form', $data);

    }

    public function import(Request $request)
    {
        set_time_limit(920);
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $csvData = file_get_contents($file);
        $rows = array_map('str_getcsv', explode("\n", $csvData));
        array_shift($rows);

        foreach ($rows as $row) {
            $new = false;
            $email = $row[4];
            $mobile = '0' . ltrim(preg_replace('/^\+?61/', '', $row[3]), '0');

            $user = User::where('email', $email)
                ->orWhere('mobile', $mobile)
                ->first();

            if (!$user) {
                $new = true;
                $user = new User();
                $user->mobile = $mobile;
                $user->email = lcfirst($email);
            }

            $user->first_name = ucfirst($row[0]);
            $user->middle_name = ucfirst($row[1]);
            $user->last_name = ucfirst($row[2]);

            $user->status = 'pending';
            $user->save();

            if ($new) {
                $group = Group::where('slug', 'customer')->first();
                $user->groups()->attach($group->id);
                $this->createReferralCode($user);
            }

        }

        return redirect()->back()->with('success', 'CSV file imported successfully.');
    }
}
