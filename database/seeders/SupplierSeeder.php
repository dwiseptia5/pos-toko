<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('suppliers')->insert([
            [
                'name' => 'PT. Indofood Sukses Makmur',
                'phone' => '02157958822',
                'address' => 'Jakarta'
            ],
            [
                'name' => 'PT. Unilever Indonesia',
                'phone' => '02180827000',
                'address' => 'Tangerang'
            ],
            [
                'name' => 'PT. Mayora Indah',
                'phone' => '02180637700',
                'address' => 'Jakarta'
            ]
        ]);
    }
}
