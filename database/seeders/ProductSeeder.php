<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Футболка',
                'description' => 'Базовая хлопковая футболка',
                'model_type' => 'tshirt',
                'base_price' => 3500,
                'price' => 3500,
            ],
            [
                'name' => 'Толстовка',
                'description' => 'Теплая толстовка с капюшоном',
                'model_type' => 'hoodie',
                'base_price' => 7000,
                'price' => 7000,
            ],
            [
                'name' => 'Платье',
                'description' => 'Классическое черное платье',
                'model_type' => 'dress',
                'base_price' => 5000,
                'price' => 5000,
            ],
            [
                'name' => 'Костюм',
                'description' => 'Деловой костюм',
                'model_type' => 'suit',
                'base_price' => 10000,
                'price' => 10000,
            ]
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['model_type' => $product['model_type']],
                $product
            );
        }
    }
}