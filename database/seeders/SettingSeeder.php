<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'setting_name' => 'General Information',
            'setting_group_slug' => 'general-information',
            'attribute' => 'site_name',
            'field_name' => 'Site Name',
            'field_type' => 'text',
        ]);
        Setting::create([
            'setting_name' => 'General Information',
            'setting_group_slug' => 'general-information',
            'attribute' => 'logo',
            'field_name' => 'Site Logo',
            'field_type' => 'file',
        ]);
        Setting::create([
            'setting_name' => 'General Information',
            'setting_group_slug' => 'general-information',
            'attribute' => 'logo_sm',
            'field_name' => 'Site Logo (Small)',
            'field_type' => 'file',
        ]);
        Setting::create([
            'setting_name' => 'General Information',
            'setting_group_slug' => 'general-information',
            'attribute' => 'working_time',
            'field_name' => 'Working Time',
            'field_type' => 'text',
        ]);
        Setting::create([
            'setting_name' => 'General Information',
            'setting_group_slug' => 'general-information',
            'attribute' => 'phone',
            'field_name' => 'Phone',
            'field_type' => 'text',
        ]);
        Setting::create([
            'setting_name' => 'General Information',
            'setting_group_slug' => 'general-information',
            'attribute' => 'email',
            'field_name' => 'Email',
            'field_type' => 'text',
        ]);
        Setting::create([
            'setting_name' => 'General Information',
            'setting_group_slug' => 'general-information',
            'attribute' => 'country',
            'field_name' => 'Country',
            'field_type' => 'text',
        ]);
        Setting::create([
            'setting_name' => 'General Information',
            'setting_group_slug' => 'general-information',
            'attribute' => 'state',
            'field_name' => 'State',
            'field_type' => 'text',
        ]);
        Setting::create([
            'setting_name' => 'General Information',
            'setting_group_slug' => 'general-information',
            'attribute' => 'street',
            'field_name' => 'Street',
            'field_type' => 'text',
        ]);
        Setting::create([
            'setting_name' => 'General Information',
            'setting_group_slug' => 'general-information',
            'attribute' => 'zip_code',
            'field_name' => 'Zip Code',
            'field_type' => 'text',
        ]);
        Setting::create([
            'setting_name' => 'General Information',
            'setting_group_slug' => 'general-information',
            'attribute' => 'vat_number',
            'field_name' => 'VAT Number',
            'field_type' => 'text',
        ]);

        Setting::create([
            'setting_name' => 'General Information',
            'setting_group_slug' => 'general-information',
            'attribute' => 'official_email',
            'field_name' => 'Official Email',
            'field_type' => 'text',
        ]);

        Setting::create([
            'setting_name' => 'General Information',
            'setting_group_slug' => 'general-information',
            'attribute' => 'official_phone',
            'field_name' => 'Official Phone',
            'field_type' => 'text',
        ]);


    }
}
