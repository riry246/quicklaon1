<?php

namespace Database\Seeders;

use App\Models\Step;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Step::create([
            'name' => 'Basic Information',
            'slug' => 'basic_information',
        ]);
        Step::create([
            'name' => 'Reason',
            'slug' => 'reason',
        ]);
        Step::create([
            'name' => 'Income',
            'slug' => 'income',
        ]);
        Step::create([
            'name' => 'Employment',
            'slug' => 'employment',
        ]);
        Step::create([
            'name' => 'Questions',
            'slug' => 'question',
        ]);
        Step::create([
            'name' => 'Terms & Conditions',
            'slug' => 'income',
        ]);
        Step::create([
            'name' => 'Choose Bank',
            'slug' => 'choose_bank',
        ]);
        Step::create([
            'name' => 'Bank Verifiaction',
            'slug' => 'bank_verification',
        ]);
        Step::create([
            'name' => 'Bank Verifiaction Checking',
            'slug' => 'bank_verification_checking',
        ]);
        Step::create([
            'name' => 'Expenses',
            'slug' => 'expenses',
        ]);
        Step::create([
            'name' => 'Create Account',
            'slug' => 'create_account',
        ]);
    }
}