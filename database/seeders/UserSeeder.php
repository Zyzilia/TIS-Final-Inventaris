<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat akun super admin utama
        User::firstOrCreate(
            ['email' => 'admin@inventaris.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // Buat akun staff gudang
        User::firstOrCreate(
            ['email' => 'staff@inventaris.com'],
            [
                'name' => 'Staff Gudang',
                'password' => Hash::make('password123'),
                'role' => 'staff',
            ]
        );
    }
}
