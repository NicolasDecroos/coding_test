<?php

use Illuminate\Database\Seeder;
use App\Customer;
use Carbon\Carbon;
class CustomerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::create([
            'name' => 'Coca Cola',
            'revenue' => '492.12',
            'created_at' => Carbon::createFromDate(2014, 06, 28),
        ]);
        Customer::create([
            'name' => 'Teamleader',
            'revenue' => '1505.95',
            'created_at' => Carbon::createFromDate(2015, 01, 15),
        ]);
        Customer::create([
            'name' => 'Jeroen De Wit',
            'revenue' => '0.00',
            'created_at' => Carbon::createFromDate(2016, 02, 11),
        ]);
    }
}