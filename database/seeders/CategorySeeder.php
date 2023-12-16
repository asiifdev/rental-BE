<?php

namespace Database\Seeders;

use App\Models\Api\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductCategory::create([
            'name' => 'Barang Elektronik'
        ]);
        ProductCategory::create([
            'name' => 'Barang Digital'
        ]);
    }
}
