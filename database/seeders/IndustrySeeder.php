<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class IndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $industryNames = [
            1 => 'Credit Union',
            2 => 'Asset Finance',
            3 => 'Trade Insurance',
            4 => 'Bank',
            5 => 'Building Society',
            6 => 'Short Term Lender',
            7 => 'Specialty Finance',
            8 => 'Mercantile Agency',
            9 => 'Telecommunication',
            10 => 'Utility',
            11 => 'Motor Finance',
            12 => 'Insurance',
            13 => 'Trade Credit',
            14 => 'Mortgage Insurance',
            15 => 'Government',
            16 => 'Consumer Rental',
            17 => 'Equipment Finance',
            18 => 'Finance Broker',
            19 => 'Store Card Finance',
            20 => 'Investment Finance',
            21 => 'Leasing',
            22 => 'Card finance',
            23 => 'Other',
            24 => 'Debt Purchase Agency',
            25 => 'Peer-to-Peer',
            26 => 'Buy Now Pay Later',
            27 => 'Community Funding',
        ];

        foreach ($industryNames as $code => $name) {
            DB::table('industries')->insert([
                'code' => $code,
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
