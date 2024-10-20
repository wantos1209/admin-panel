<?php

namespace Database\Seeders;

use App\Models\Subarea;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubareaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Subarea::create([
            'id' => 1,
            'area_id' => 1,
            'subarea_nama' => 'BATAM KOTA'
        ]);

        Subarea::create([
            'id' => 2,
            'area_id' => 1,
            'subarea_nama' => 'BATU AJI'
        ]);
    }
}
