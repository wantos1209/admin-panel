<?php

namespace Database\Seeders;

use App\Models\Pengirimandetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PengirimanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pengirimandetail::create([
            'id' => 1,
            'pengiriman_id' => 1,
            'subarea_id' => 1,
            'no_stt' => '95LP1727930360984'
        ]);

        Pengirimandetail::create([
            'id' => 2,
            'pengiriman_id' => 2,
            'subarea_id' => 1,
            'no_stt' => '95LP1727989053662'
        ]);
    }
}
