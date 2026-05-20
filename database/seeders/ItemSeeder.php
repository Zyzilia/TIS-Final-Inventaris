<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        // Insert suppliers
        $suppliers = [
            ['name' => 'NVIDIA Corp', 'address' => 'Silicon Valley, California, USA', 'phone' => '+1-555-0199'],
            ['name' => 'AMD Corp', 'address' => 'Santa Clara, California, USA', 'phone' => '+1-555-0120'],
            ['name' => 'Corsair Memory', 'address' => 'Fremont, California, USA', 'phone' => '+1-555-0143'],
            ['name' => 'Samsung Corp', 'address' => 'Suwon, South Korea', 'phone' => '+82-2-1234-5678'],
            ['name' => 'ASUS Global', 'address' => 'Beitou District, Taipei, Taiwan', 'phone' => '+886-2-8143-7575'],
            ['name' => 'NZXT Corp', 'address' => 'Los Angeles, California, USA', 'phone' => '+1-555-0177']
        ];

        foreach ($suppliers as $sup) {
            DB::table('suppliers')->insertOrIgnore([
                'name' => $sup['name'],
                'address' => $sup['address'],
                'phone' => $sup['phone'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert dummy categories
        $categories = [
            ['name' => 'GPU'],
            ['name' => 'CPU'],
            ['name' => 'RAM'],
            ['name' => 'Storage'],
            ['name' => 'Motherboard'],
            ['name' => 'PSU'],
            ['name' => 'Case'],
            ['name' => 'Cooling']
        ];
        
        foreach ($categories as $cat) {
            DB::table('categories')->insertOrIgnore([
                'name' => $cat['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $nvidiaId = DB::table('suppliers')->where('name', 'NVIDIA Corp')->first()->id ?? null;
        $amdId = DB::table('suppliers')->where('name', 'AMD Corp')->first()->id ?? null;
        $corsairId = DB::table('suppliers')->where('name', 'Corsair Memory')->first()->id ?? null;
        $samsungId = DB::table('suppliers')->where('name', 'Samsung Corp')->first()->id ?? null;
        $asusId = DB::table('suppliers')->where('name', 'ASUS Global')->first()->id ?? null;
        $nzxtId = DB::table('suppliers')->where('name', 'NZXT Corp')->first()->id ?? null;

        $gpuId = DB::table('categories')->where('name', 'GPU')->first()->id;
        $cpuId = DB::table('categories')->where('name', 'CPU')->first()->id;
        $ramId = DB::table('categories')->where('name', 'RAM')->first()->id;
        $storageId = DB::table('categories')->where('name', 'Storage')->first()->id;
        $mbId = DB::table('categories')->where('name', 'Motherboard')->first()->id;
        $psuId = DB::table('categories')->where('name', 'PSU')->first()->id;
        $caseId = DB::table('categories')->where('name', 'Case')->first()->id;
        $coolId = DB::table('categories')->where('name', 'Cooling')->first()->id;

        $items = [
            [
                'category_id' => $gpuId,
                'supplier_id' => $nvidiaId,
                'brand' => 'Nvidia GeForce',
                'name' => 'NVIDIA RTX 4090 GPU',
                'sku' => 'GPU-4090-FE',
                'stock' => 15,
                'price_usd' => 1700.00,
                'profit_margin' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $cpuId,
                'supplier_id' => $amdId,
                'brand' => 'AMD Ryzen',
                'name' => 'AMD Ryzen 9 7950X',
                'sku' => 'CPU-AMD-7950',
                'stock' => 32,
                'price_usd' => 580.00,
                'profit_margin' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $ramId,
                'supplier_id' => $corsairId,
                'brand' => 'Corsair Vengeance',
                'name' => 'Corsair Vengeance 32GB DDR5',
                'sku' => 'RAM-COR-32D5',
                'stock' => 85,
                'price_usd' => 130.00,
                'profit_margin' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $storageId,
                'supplier_id' => $samsungId,
                'brand' => 'Samsung PRO/EVO',
                'name' => 'Samsung 990 PRO 2TB NVMe',
                'sku' => 'SSD-SAM-2TB',
                'stock' => 120,
                'price_usd' => 200.00,
                'profit_margin' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $mbId,
                'supplier_id' => $asusId,
                'brand' => 'ASUS ROG/TUF/Prime',
                'name' => 'ASUS ROG Crosshair X670E',
                'sku' => 'MB-ASUS-X670',
                'stock' => 20,
                'price_usd' => 520.00,
                'profit_margin' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $psuId,
                'supplier_id' => $corsairId,
                'brand' => 'Corsair RM/SF-Series',
                'name' => 'Corsair RM1000x 1000W 80+ Gold',
                'sku' => 'PSU-COR-1000X',
                'stock' => 40,
                'price_usd' => 180.00,
                'profit_margin' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $caseId,
                'supplier_id' => $nzxtId,
                'brand' => 'NZXT H-Series',
                'name' => 'NZXT H9 Flow Dual-Chamber',
                'sku' => 'CAS-NZX-H9F',
                'stock' => 25,
                'price_usd' => 160.00,
                'profit_margin' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $coolId,
                'supplier_id' => $nzxtId,
                'brand' => 'NZXT Kraken',
                'name' => 'NZXT Kraken Elite 360 RGB',
                'sku' => 'COOL-NZX-K360',
                'stock' => 30,
                'price_usd' => 280.00,
                'profit_margin' => 18,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('items')->insertOrIgnore($items);
    }
}
