<?php

namespace App\Http\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class SettingHelper
{
    protected $model;

    public function __construct(Setting $model)
    {
        $this->model = $model;
    }

    public function logo($attribute)
    {
        $setting = $this->model->where('attribute', $attribute)->first();
        $logo = $setting->value ?? '';
        return $logo;
    }

    public function attribute($value)
    {
        $setting = $this->model->where('value', $value)->first();
        $attribute = $setting->attribute;
        return $attribute;
    }

    public function getAdminGroup()
    {
        $user = Auth::user();
        $usergroup  = null;

        // Assuming the 'groups' relationship is defined on the User model
        $groups = $user->groups;

        // Assuming each user can be associated with multiple groups
        foreach ($groups as $group) {
           // echo "Group Slug: " . $group->slug . PHP_EOL;
            $usergroup =  $group->slug;
        }

        return $usergroup;

    }
}
