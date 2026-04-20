<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Setting;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Auth;

class SettingController extends Controller
{
    private $module_name = 'Settings';
    private $module = 'setting';
    private $url = 'setting.index';

    use GeneralTrait;

    public function view($slug)
    {
        $value = Setting::where('setting_group_slug', $slug)->get();

        //Slug
        $data['slug'] = $slug;

        //Managing Breadcrumb
        $data['breadcrumb'] = $this->breadcrumb($this->module_name, 'Group Edit', $this->url, null);

        $arr = array();
        foreach ($value as $v) {
            $arr[] = array(
                'name' => $v->attribute,
                'size' => '4',
                'type' => $v->field_type,
                'placeholder' => $v->field_name,
                'value' => isset($v->value) ? $v->value : ''
            );

            // dd($arr);
        }

        $data['form'] = array(
            'action' => 'setting.update',
            'id' => isset($value->id) ? $value->id : '',
            'form_elements' => array(
                array(
                    'form_name' => $slug,
                    'size' => '12',
                    'form_fields' => $arr
                )
            ),
        );



        //  dd($data);

        return view('admin.settings.form', $data);


        //return view('admin.site_settings.site_settings', compact('siteSettings', 'slug'));
    }

    public function update(Request $request, $slug)
    {
        $slugValue = $slug;
        $data = $request->all();
        $settingData = Setting::where('setting_group_slug', $slugValue)->get();

        foreach ($settingData as $set) {
            $settings = Setting::findOrFail($set->id);
            $settings->value = $data[$set->attribute] ?? $set->value;

            if (!empty($request->file('logo'))) {
                $logo = $request->file('logo');
                $logoExtension = $logo->getClientOriginalExtension();
                $logoName = $slugValue . '-logo-' . time() . '.' . strtolower($logoExtension);
                $data['logo'] = $logoName;
                $logoSuccess = true;
            }

            if (!empty($request->file('logo_sm'))) {

                $smallLogo = $request->file('logo_sm');
                $smallLogoExtension = $smallLogo->getClientOriginalExtension();
                $smallLogoName = $slugValue . '-small-logo-' . time() . '.' . strtolower($smallLogoExtension);
                $data['logo_sm'] = $smallLogoName;
                $smallLogoSuccess = true;
            }

            $update = $settings->save();

            if ($update) {
                if (!empty($request->file('logo')) && $set->attribute == 'logo' && isset($logoSuccess)) {
                    if ($set->attribute == 'logo' && $set->value != null) {
                        @unlink(storage_path() . '/app/public/uploads/settings/General/Logo/' . $set->value);
                    }
                    Storage::putFileAs('public/uploads/settings/General/Logo/', $logo, $logoName);
                    Image::make(storage_path() . '/app/public/uploads/settings/General/Logo/' . $logoName)->save();
                }
                if (!empty($request->file('logo_sm')) && $set->attribute == 'logo_sm' && isset($smallLogoSuccess)) {
                    if ($set->attribute == 'logo_sm' && $set->value != null) {
                        @unlink(storage_path() . '/app/public/uploads/settings/General/Small Logo/' . $set->value);
                    }
                    Storage::putFileAs('public/uploads/settings/General/Small Logo/', $smallLogo, $smallLogoName);
                    Image::make(storage_path() . '/app/public/uploads/settings/General/Small Logo/' . $smallLogoName)->save();
                }
            } else {
                return back();
            }
        }
        return back();
    }

    
}
