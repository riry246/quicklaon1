<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'module_action_id' => 1,
                'group_id' => 1,
                'granted_by' => 1,
                'granted_at' => '2023-09-28 00:00:00'
            ],
            [
                'module_action_id' => 2,
                'group_id' => 1,
                'granted_by' => 1,
                'granted_at' => '2023-09-28 00:00:00'
            ],
            [
                'module_action_id' => 3,
                'group_id' => 1,
                'granted_by' => 1,
                'granted_at' => '2023-09-28 00:00:00'
            ],
            [
                'module_action_id' => 4,
                'group_id' => 1,
                'granted_by' => 1,
                'granted_at' => '2023-09-28 00:00:00'
            ],
            [
                'module_action_id' => 5,
                'group_id' => 1,
                'granted_by' => 1,
                'granted_at' => '2023-09-28 00:00:00'
            ],
            [
                'module_action_id' => 6,
                'group_id' => 1,
                'granted_by' => 1,
                'granted_at' => '2023-09-28 00:00:00'
            ],
            [
                'module_action_id' => 7,
                'group_id' => 1,
                'granted_by' => 1,
                'granted_at' => '2023-09-28 00:00:00'
            ],
            [
                'module_action_id' => 8,
                'group_id' => 1,
                'granted_by' => 1,
                'granted_at' => '2023-09-28 00:00:00'
            ],
            [
                'module_action_id' => 9,
                'group_id' => 1,
                'granted_by' => 1,
                'granted_at' => '2023-09-28 00:00:00'
            ],
            [
                'module_action_id' => 10,
                'group_id' => 1,
                'granted_by' => 1,
                'granted_at' => '2023-09-28 00:00:00'
            ],
            [
                'module_action_id' => 11,
                'group_id' => 1,
                'granted_by' => 1,
                'granted_at' => '2023-09-28 00:00:00'
            ],
            [
                'module_action_id' => 12,
                'group_id' => 1,
                'granted_by' => 1,
                'granted_at' => '2023-09-28 00:00:00'
            ],
            [
                'module_action_id' => 13,
                'group_id' => 1,
                'granted_by' => 1,
                'granted_at' => '2023-09-28 00:00:00'
            ],
            [
                'module_action_id' => 14,
                'group_id' => 1,
                'granted_by' => 1,
                'granted_at' => '2023-09-28 00:00:00'
            ],
            [
                'module_action_id' => 15,
                'group_id' => 1,
                'granted_by' => 1,
                'granted_at' => '2023-09-28 00:00:00'
            ],
            [
                'module_action_id' => 16,
                'group_id' => 1,
                'granted_by' => 1,
                'granted_at' => '2023-09-28 00:00:00'
            ],
            [
                'module_action_id' => 1,
                'group_id' => 2,
                'granted_by' => 1,
                'granted_at' => '2023-09-28 00:00:00'
            ],
            [
                'module_action_id' => 2,
                'group_id' => 2,
                'granted_by' => 1,
                'granted_at' => '2023-09-28 00:00:00'
            ],
            [
                'module_action_id' => 3,
                'group_id' => 2,
                'granted_by' => 1,
                'granted_at' => '2023-09-28 00:00:00'
            ],
            [
                'module_action_id' => 4,
                'group_id' => 2,
                'granted_by' => 1,
                'granted_at' => '2023-09-28 00:00:00'
            ],
        ];

        Permission::insert($data);
    }
}
