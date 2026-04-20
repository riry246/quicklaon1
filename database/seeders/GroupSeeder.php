<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Group;
use Illuminate\Support\Str;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groupsData = [
            ['name' => 'Super Admin', 'slug' => Str::slug('Super Admin')],
            ['name' => 'Admin', 'slug' => Str::slug('Admin')],
            ['name' => 'Customer', 'slug' => Str::slug('Customer')],
            ['name' => 'Customer Service Representative', 'slug' => Str::slug('Customer Service Representative')],

            // Add more group data as needed
        ];

        Group::insert($groupsData);
    }
}