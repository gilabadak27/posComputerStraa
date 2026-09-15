<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::firstOrCreate(
            ['email' => 'budi@gmail.com'],
            [
                'name' => 'Budi Santoso',
                'phone' => '081234567890',
                'address' => 'Jl. Sudirman No. 45, Jakarta',
                'points' => 150,
                'status' => 'active',
            ]
        );

        Customer::firstOrCreate(
            ['email' => 'siti@gmail.com'],
            [
                'name' => 'Siti Rahma',
                'phone' => '085712345678',
                'address' => 'Jl. Manggis No. 12, Bandung',
                'points' => 80,
                'status' => 'active',
            ]
        );

        Customer::firstOrCreate(
            ['email' => 'andi@gmail.com'],
            [
                'name' => 'Andi Wijaya',
                'phone' => '088198765432',
                'address' => 'Jl. Pemuda No. 88, Surabaya',
                'points' => 210,
                'status' => 'active',
            ]
        );
    }
}
