<?php

use Illuminate\Database\Seeder;
use \App\Product;
class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'id' => 'A101',
            'description' => 'Screwdriver',
            'price' => 9.75,
            'category' => 1
        ]);

        Product::create([
            'id' => 'A102',
            'description' => 'Basic on-off switch',
            'price' => 4.99,
            'category' => 1
        ]);

        Product::create([
            'id' => 'B101',
            'description' => 'Basic on-off switch',
            'price' => 4.99,
            'category' => 2
        ]);

        Product::create([
            'id' => 'B102',
            'description' => 'Press button',
            'price' => 4.99,
            'category' => 2
        ]);

        Product::create([
            'id' => 'B103',
            'description' => 'Switch with motion detector',
            'price' => 12.95,
            'category' => 2
        ]);
    }
}
