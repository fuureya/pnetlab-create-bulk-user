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
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Product::create([
            'name' => 'Paket Basic (1 Bulan)',
            'duration_days' => 30,
            'price' => 'Rp 50.000',
            'description' => 'Akses penuh selama 1 bulan.',
            'features' => ['Akses semua lab', 'Support komunitas', 'Tanpa batasan jam'],
            'is_recommended' => false
        ]);

        Product::create([
            'name' => 'Paket Pro (3 Bulan)',
            'duration_days' => 90,
            'price' => 'Rp 135.000',
            'description' => 'Lebih hemat untuk akses 3 bulan berturut-turut.',
            'features' => ['Akses semua lab', 'Support prioritas', 'Tanpa batasan jam', 'Diskon khusus'],
            'is_recommended' => true
        ]);

        Product::create([
            'name' => 'Paket Enterprise (6 Bulan)',
            'duration_days' => 180,
            'price' => 'Rp 250.000',
            'description' => 'Akses jangka panjang untuk persiapan ujian atau project besar.',
            'features' => ['Akses semua lab', 'Support 1-on-1', 'Tanpa batasan jam', 'Free update lab baru', 'Sertifikat penyelesaian'],
            'is_recommended' => false
        ]);
    }
}
