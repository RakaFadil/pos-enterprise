<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category; // Wajib dipanggil
use App\Models\Product;  // Wajib dipanggil

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Injeksi Kategori Utama
        $makanan = Category::create(['name' => 'Makanan Ringan', 'description' => 'Berbagai macam snack dan biskuit']);
        $minuman = Category::create(['name' => 'Minuman Dingin', 'description' => 'Minuman kemasan di dalam kulkas']);
        $sembako = Category::create(['name' => 'Sembako Dasar', 'description' => 'Kebutuhan pokok dapur']);

        // 2. Injeksi Puluhan Produk (Beserta Harga dan Stoknya!)
                // 2. Injeksi Puluhan Produk (Beserta Harga Jual dan Harga Modalnya!)
        $products = [
            // --- Rak Makanan ---
            ['category_id' => $makanan->id, 'name' => 'Chitato Sapi Panggang 68g', 'harga_modal' => 8500, 'price' => 11000, 'stock' => 50, 'description' => 'Keripik kentang rasa sapi panggang'],
            ['category_id' => $makanan->id, 'name' => 'Oreo Supreme Hitam', 'harga_modal' => 11000, 'price' => 15000, 'stock' => 35, 'description' => 'Biskuit hitam krim merah'],
            ['category_id' => $makanan->id, 'name' => 'Taro Net Seaweed', 'harga_modal' => 4500, 'price' => 6000, 'stock' => 100, 'description' => 'Snack ringan rumput laut'],

            // --- Rak Minuman ---
            ['category_id' => $minuman->id, 'name' => 'Coca Cola 1.5 Liter', 'harga_modal' => 14000, 'price' => 16500, 'stock' => 20, 'description' => 'Minuman soda botol besar'],
            ['category_id' => $minuman->id, 'name' => 'Teh Pucuk Harum 350ml', 'harga_modal' => 3000, 'price' => 4500, 'stock' => 120, 'description' => 'Teh melati manis segar'],
            ['category_id' => $minuman->id, 'name' => 'Aqua Mineral 600ml', 'harga_modal' => 2000, 'price' => 3500, 'stock' => 200, 'description' => 'Air mineral pegunungan'],
            ['category_id' => $minuman->id, 'name' => 'Pocari Sweat Can', 'harga_modal' => 6500, 'price' => 8000, 'stock' => 2, 'description' => 'Minuman isotonik'], 

            // --- Rak Sembako ---
            ['category_id' => $sembako->id, 'name' => 'Beras Pandan Wangi 5Kg', 'harga_modal' => 77000, 'price' => 85000, 'stock' => 15, 'description' => 'Beras putih pulen premium'],
            ['category_id' => $sembako->id, 'name' => 'Minyak Goreng Bimoli 2L', 'harga_modal' => 32000, 'price' => 38000, 'stock' => 40, 'description' => 'Minyak sawit murni pouch'],
            ['category_id' => $sembako->id, 'name' => 'Gula Pasir Gulaku 1Kg', 'harga_modal' => 14500, 'price' => 16000, 'stock' => 60, 'description' => 'Gula tebu murni'],
            ['category_id' => $sembako->id, 'name' => 'Indomie Goreng Original', 'harga_modal' => 2400, 'price' => 3000, 'stock' => 300, 'description' => 'Mie instan goreng sejuta umat'],
        ];


        // Eksekusi penanaman massal ke Database
        foreach ($products as $p) {
            Product::create($p);
        }
    }
}