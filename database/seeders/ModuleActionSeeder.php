<?php

namespace Database\Seeders;

use App\Models\ModuleAction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'module_id' => 1,
                'action' => 'View',
            ],
            [
                'module_id' => 1,
                'action' => 'Create',
            ],
            [
                'module_id' => 1,
                'action' => 'Update',
            ],
            [
                'module_id' => 1,
                'action' => 'Delete',
            ],
            [
                'module_id' => 2,
                'action' => 'View',
            ],
            [
                'module_id' => 2,
                'action' => 'Create',
            ],
            [
                'module_id' => 2,
                'action' => 'Update',
            ],
            [
                'module_id' => 2,
                'action' => 'Delete',
            ],
            [
                'module_id' => 3,
                'action' => 'View',
            ],
            [
                'module_id' => 3,
                'action' => 'Create',
            ],
            [
                'module_id' => 3,
                'action' => 'Update',
            ],
            [
                'module_id' => 3,
                'action' => 'Delete',
            ],
            [
                'module_id' => 4,
                'action' => 'View',
            ],
            [
                'module_id' => 4,
                'action' => 'Create',
            ],
            [
                'module_id' => 4,
                'action' => 'Update',
            ],
            [
                'module_id' => 4,
                'action' => 'Delete',
            ],
        ];

        ModuleAction::insert($data);
    }
}
