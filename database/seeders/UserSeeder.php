<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usersData = [
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'superadmin@cashfaster.com',
                'password' => bcrypt('super')
            ],
            [
                'first_name' => 'Admin',
                'last_name' => ' ',
                'email' => 'admin@cashfaster.com',
                'password' => bcrypt('admin')
            ]
        ];

        User::insert($usersData);
    }
}
