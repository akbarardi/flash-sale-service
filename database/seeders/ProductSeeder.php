<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Baju Hitam Polos',
            'price' => 66000,
            'stock' => 10
        ]);

        Product::create([
            'name' => 'Iphone 17 Pro Max',
            'price' => 6,
            'stock' => 2
        ]);
    }
}
