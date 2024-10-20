<?php

namespace Database\Seeders;

use App\Models\Userapk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserapkTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Userapk::create([
            'subarea_id' => 1,
            'username' => 'kurir-batamkota',
            'password' => bcrypt('123456789')
        ]);

        Userapk::create([
            'subarea_id' => 2,
            'username' => 'kurir-batuaji',
            'password' => bcrypt('123456789')
        ]);
    }
}
