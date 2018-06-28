<?php

use Illuminate\Database\Seeder;

use App\Discount;
class DiscountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Discount::create([
            'name' => 'Revenue Discount',
            'description' => 'Our loyal customers get a 10% discount!'
        ]);
        Discount::create([
            'name' => 'Switches Discount',
            'description' => "Seems like you really like Switches, here's one for free!"
        ]);
        Discount::create([
            'name' => 'Tools Discount',
            'description' => "Seems like you really like Tools, here's one for free!"
        ]);
    }
}

