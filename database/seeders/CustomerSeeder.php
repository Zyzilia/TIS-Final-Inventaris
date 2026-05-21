<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('customers')->insert([
            [
                'name' => 'Quantum PC Shop',
                'type' => 'Wholesale Distributor',
                'phone' => '+62-812-3456-7890',
                'location' => 'Jakarta, Indonesia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Toko Abadi Jaya',
                'type' => 'Retail Store',
                'phone' => '+62-821-9876-5432',
                'location' => 'Bandung, Indonesia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Medan Tech Store',
                'type' => 'Retail Store',
                'phone' => '+62-853-1111-2222',
                'location' => 'Medan, Indonesia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Surya Komputer',
                'type' => 'Wholesale Distributor',
                'phone' => '+62-878-3333-4444',
                'location' => 'Surabaya, Indonesia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
