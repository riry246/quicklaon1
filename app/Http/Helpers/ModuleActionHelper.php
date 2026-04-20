<?php

namespace App\Http\Helpers;

use App\Models\ModuleAction;

class ModuleActionHelper
{
    protected $model;

    public function __construct(ModuleAction $model)
    {
        $this->model = $model;
    }

    public function dropdown()
    {
        return $this->model->get()->groupBy('module_id');
    }

    public function selected($permissions)
    {
        return $permissions->pluck('module_action_id')->toArray();
    }
}
