<?php

namespace App\Http\Helpers;

use App\Models\Permission;

class PermissionHelper
{
    protected $model;

    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    public function dropdown()
    {
        return $this->model->get()->groupBy('group_id');
    }

    public function selected($permissions)
    {
        return $permissions->pluck('id')->toArray();
    }
}
