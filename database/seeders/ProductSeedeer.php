<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductSeedeer extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Create 50 fake products
        for ($i = 0; $i < 100; $i++) {
            Product::create([
                'name' => $faker->word,
                'description' => $faker->sentence,
                'image' => $faker->imageUrl(200, 200, 'products', true),
                'type' => $faker->randomElement(['Electronics', 'Furniture', 'Clothing', 'Food']),
                'quantity' => $faker->numberBetween(1, 100),
                'price' => $faker->numberBetween(100, 1000),
            ]);
        }
    }
}
