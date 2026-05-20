<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ActivityLog::create([
            'user_name' => 'Budi Santoso',
            'action' => 'Paid',
            'description' => 'Budi Santoso purchased NVIDIA RTX 4090 GPU',
            'item_type' => 'gpu',
            'amount' => 'Rp 28.000.000',
            'order_id' => '#513 003',
        ]);

        ActivityLog::create([
            'user_name' => 'Siti Aminah',
            'action' => 'Refund',
            'description' => 'Siti Aminah returned Corsair Vengeance RAM',
            'item_type' => 'ram',
            'amount' => 'Rp 2.100.000',
            'order_id' => '#152 004',
        ]);

        ActivityLog::create([
            'user_name' => 'Joko Widodo',
            'action' => 'Paid',
            'description' => 'Joko Widodo purchased AMD Ryzen 9 7950X',
            'item_type' => 'cpu',
            'amount' => 'Rp 11.600.000',
            'order_id' => '#486 005',
        ]);

        ActivityLog::create([
            'user_name' => 'Rina Nose',
            'action' => 'Paid',
            'description' => 'Rina Nose purchased Samsung 990 PRO SSD',
            'item_type' => 'ssd',
            'amount' => 'Rp 3.200.000',
            'order_id' => '#782 006',
        ]);
    }
}
