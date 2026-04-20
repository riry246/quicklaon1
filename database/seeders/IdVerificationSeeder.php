<?php

namespace Database\Seeders;

use App\Models\IdVerification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IdVerificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       IdVerification::create([
        'name' => 'Passport',
        'slug' => 'passport',
        'fields' => '{"name":"John","number":12345}',
    ]);
       IdVerification::create([
        'name' => 'Citizenship',
        'slug' => 'citizenship',
        'fields' => '{"name":"John","number":12345}',
    ]);
       IdVerification::create([
        'name' => 'Driving Licence',
        'slug' => 'friving licence',
        'fields' => '{"name":"John","number":12345}',
    ]);
       IdVerification::create([
        'name' => 'Centerlink',
        'slug' => 'centerlink',
        'fields' => '{"BirthDate":"date","Name":text,"CardType":"number",""}',
    ]);
    }
}
