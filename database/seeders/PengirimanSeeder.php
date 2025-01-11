<?php

namespace Database\Seeders;

use App\Models\Pengiriman;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PengirimanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pengiriman::create([
            'id' => 1,
            'userapk_id' => 1,
            'subarea_id' => 1,
            'nomor' => 'P2411001'
        ]);

        Pengiriman::create([
            'id' => 2,
            'userapk_id' => 1,
            'subarea_id' => 2,
            'nomor' => 'P2411002'
        ]);
    }
}
