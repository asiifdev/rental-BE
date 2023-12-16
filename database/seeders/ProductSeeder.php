<?php

namespace Database\Seeders;

use App\Models\Api\Brand;
use App\Models\Api\Product;
use App\Models\Api\Stock;
use App\Models\Api\Transaction;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 1000; $i++) {
            $id = rand(1, 2);
            $product = new Product();
            $product->category_id = $id;
            $product->name = Factory::create()->name();
            $product->brand = Factory::create()->name();
            $product->qty = $i;
            $product->save();

            $product_id = $product->id;
            Brand::create([
                'name' => $product->brand,
                'product_category_id' => $id
            ]);

            Stock::create(
                [
                    'product_id' => $product_id,
                    'qty' => $product->qty,
                ]
            );

            Transaction::create([
                'product_id' => $product_id,
                'user_input_id' => 1,
                'jenis' => 'in',
                'qty' => $product->qty
            ]);
        }
    }
}
