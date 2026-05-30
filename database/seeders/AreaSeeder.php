<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            ['code' => 'AR-BDG', 'name' => 'Area Gedung Utama', 'latitude' => -8.409518, 'longitude' => 115.188916, 'address' => 'Jl. Raya Utama No. 1, Bali'],
            ['code' => 'AR-PKR', 'name' => 'Area Parkir', 'latitude' => -8.409800, 'longitude' => 115.189200, 'address' => 'Area Parkir Timur'],
            ['code' => 'AR-GDN', 'name' => 'Area Gudang', 'latitude' => -8.410100, 'longitude' => 115.189500, 'address' => 'Kompleks Gudang'],
            ['code' => 'AR-TRH', 'name' => 'Area Taman', 'latitude' => -8.409200, 'longitude' => 115.188600, 'address' => 'Taman Belakang'],
            ['code' => 'AR-BLKG', 'name' => 'Area Belakang', 'latitude' => -8.409900, 'longitude' => 115.188300, 'address' => 'Area Belakang Kantor'],
        ];

        foreach ($areas as $area) {
            Area::create($area + ['radius' => 30, 'is_active' => true]);
        }
    }
}
