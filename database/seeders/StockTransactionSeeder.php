<?php

namespace Database\Seeders;

use App\Models\StockTransaction;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;

class StockTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $adminId = $admin ? $admin->id : 1;

        $items = Item::all();

        if ($items->count() === 0) {
            return;
        }

        // 1. Restock GPU
        $gpu = Item::where('sku', 'GPU-4090-FE')->first();
        if ($gpu) {
            StockTransaction::create([
                'item_id' => $gpu->id,
                'user_id' => $adminId,
                'type' => 'in',
                'quantity' => 20,
                'notes' => 'Restock from NVIDIA Corp Corp HQ',
                'created_at' => now()->subDays(5),
            ]);

            StockTransaction::create([
                'item_id' => $gpu->id,
                'user_id' => $adminId,
                'type' => 'out',
                'quantity' => 5,
                'notes' => 'Sent to Jakarta Distribution Center',
                'created_at' => now()->subDays(4),
            ]);
        }

        // 2. Restock CPU
        $cpu = Item::where('sku', 'CPU-AMD-7950')->first();
        if ($cpu) {
            StockTransaction::create([
                'item_id' => $cpu->id,
                'user_id' => $adminId,
                'type' => 'in',
                'quantity' => 40,
                'notes' => 'Inbound shipment AMD Taiwan',
                'created_at' => now()->subDays(3),
            ]);

            StockTransaction::create([
                'item_id' => $cpu->id,
                'user_id' => $adminId,
                'type' => 'out',
                'quantity' => 8,
                'notes' => 'Delivered to Bandung Retail Partner',
                'created_at' => now()->subDays(2),
            ]);
        }

        // 3. Restock RAM
        $ram = Item::where('sku', 'RAM-COR-32D5')->first();
        if ($ram) {
            StockTransaction::create([
                'item_id' => $ram->id,
                'user_id' => $adminId,
                'type' => 'in',
                'quantity' => 100,
                'notes' => 'Bulk Corsair Restock',
                'created_at' => now()->subDays(1),
            ]);

            StockTransaction::create([
                'item_id' => $ram->id,
                'user_id' => $adminId,
                'type' => 'out',
                'quantity' => 15,
                'notes' => 'Distributed to Surabaya Warehouse',
                'created_at' => now()->subHours(12),
            ]);
        }
    }
}
