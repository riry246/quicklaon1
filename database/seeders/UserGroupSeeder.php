<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Group;
use Illuminate\Support\Facades\DB;

class UserGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userGroupsData = [
            ['user_id' => 1, 'group_id' => 1],
            ['user_id' => 2, 'group_id' => 1]

        ];

        DB::table('group_user')->insert($userGroupsData);
    }
}
