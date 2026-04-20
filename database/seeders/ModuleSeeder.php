<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'User Management',
                'description' => 'User Management',
            ],
            [
                'name' => 'User Groups',
                'description' => 'User Groups',
            ],
            [
                'name' => 'Modules',
                'description' => 'Modules',
            ],
            [
                'name' => 'Module Action',
                'description' => 'Module Action',
            ],
        ];

        Module::insert($data);
    }
}
