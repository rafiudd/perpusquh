<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Product::create([
            'code' => 'MTA-18289582',
            'name' => 'Abacavir',
            'stock' => '100',
            'unit'  => 'strip',
            'price' => '20000',
            'description' => 'Abacavir adalah obat antivirus untuk mengobati infeksi HIV.',
            'image' => '/images/abacavir.webp',
            'expired_date' => '2023-06-29 00:00:00'
        ]);
    }
}
