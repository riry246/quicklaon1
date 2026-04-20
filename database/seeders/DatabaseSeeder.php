<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
           // GroupSeeder::class,
            UserGroupSeeder::class,
          //  SettingSeeder::class,
          //  StepSeeder::class,
          //  ModuleSeeder::class,
          //  ModuleActionSeeder::class,
          //  PermissionSeeder::class,
          //  IdVerificationSeeder::class,
        ]);
    }
}
