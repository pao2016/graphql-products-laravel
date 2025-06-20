<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Product::insert([
            [
                'name' => 'Laptop HP 15"',
                'description' => 'Laptop con procesador AMD Ryzen 5',
                'price' => 2800000,
                'category_id' => 1,
            ],
            [
                'name' => 'Sofá 3 puestos',
                'description' => 'Sofá color gris oscuro',
                'price' => 1500000,
                'category_id' => 2,
            ],
            [
                'name' => 'Chaqueta impermeable',
                'description' => 'Chaqueta para clima frío',
                'price' => 250000,
                'category_id' => 3,
            ],
        ]);
    }
}
