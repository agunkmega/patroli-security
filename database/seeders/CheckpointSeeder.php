<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Checkpoint;
use Illuminate\Database\Seeder;

class CheckpointSeeder extends Seeder
{
    public function run(): void
    {
        $areas = Area::all();
        $checkpoints = [];

        foreach ($areas as $area) {
            $lat = (float)$area->latitude;
            $lng = (float)$area->longitude;

            $points = [
                [
                    'code' => $area->code . '-01',
                    'name' => "Pos {$area->name} - Pintu Masuk",
                    'latitude' => $lat + 0.0001,
                    'longitude' => $lng + 0.0001,
                    'order' => 1,
                    'description' => 'Pintu masuk utama area ' . $area->name,
                ],
                [
                    'code' => $area->code . '-02',
                    'name' => "Pos {$area->name} - Tengah",
                    'latitude' => $lat + 0.0003,
                    'longitude' => $lng + 0.0002,
                    'order' => 2,
                    'description' => 'Titik tengah area ' . $area->name,
                ],
                [
                    'code' => $area->code . '-03',
                    'name' => "Pos {$area->name} - Belakang",
                    'latitude' => $lat + 0.0005,
                    'longitude' => $lng + 0.0003,
                    'order' => 3,
                    'description' => 'Pintu belakang area ' . $area->name,
                ],
            ];

            foreach ($points as $point) {
                $checkpoint = Checkpoint::create([
                    'code' => $point['code'],
                    'name' => $point['name'],
                    'description' => $point['description'] ?? null,
                    'area_id' => $area->id,
                    'latitude' => $point['latitude'],
                    'longitude' => $point['longitude'],
                    'radius' => 30,
                    'order' => $point['order'],
                    'require_photo' => false,
                    'is_active' => true,
                ]);
                $checkpoints[] = $checkpoint;
            }
        }
    }
}
