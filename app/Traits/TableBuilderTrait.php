<?php

namespace App\Traits;

trait TableBuilderTrait
{
    public function defaultButton($module)
    {
        $btn = array(
            'url' => $module . '.create',
            'name' => 'Add New',
            'color' => 'default',
            'icon' => 'plus'

        );
        return $btn;
    }
    public function disableButton($module)
    {
        $btn = array(
            'url' => '',
            'name' => '',
            'color' => 'default',
            'icon' => ''

        );
        return $btn;
    }

    public function actionButton($module)
    {
        if ($module == 'loan') {
            $btn[] = array(
                'url' => $module . '.view',
                'name' => 'View',
                'color' => 'success',
                'icon' => 'ri-eye-line',
                'class' => 'btn-success-transparent rounded-pill btn-wave'
            );
        }
        if ($module == 'customer') {
            $btn[] = array(
                'url' => $module . '.view',
                'name' => 'View',
                'color' => 'success',
                'icon' => 'ri-file-history-fill',
                'class' => 'btn-success-transparent rounded-pill btn-wave'
            );
        }
        $btn[] = array(
            'url' => $module . '.edit',
            'name' => 'Edit',
            'color' => 'success',
            'icon' => 'ri-edit-line',
            'class' => 'btn-primary-transparent rounded-pill btn-wave'
        );
        $btn[] = array(
            'url' => $module . '.delete',
            'name' => 'Delete',
            'color' => 'danger',
            'icon' => 'ri-delete-bin-line',
            'class' => 'btn-danger-transparent rounded-pill btn-wave me-5'
        );
        return $btn;
    }
    public function actionViewButton($module)
    {
       
            $btn[] = array(
                'url' => $module . '.view',
                'name' => 'View',
                'color' => 'success',
                'icon' => 'ri-eye-line',
                'class' => 'btn-success-transparent rounded-pill btn-wave'
            );
     
        
        return $btn;
    }
    public function actionEditButton($module)
    {
       
        $btn[] = array(
            'url' => $module . '.edit',
            'name' => 'Edit',
            'color' => 'success',
            'icon' => 'ri-edit-line',
            'class' => 'btn-primary-transparent rounded-pill btn-wave'
        );
     
        
        return $btn;
    }
}
