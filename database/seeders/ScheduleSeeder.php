<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Checkpoint;
use App\Models\Guard;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $guards = Guard::all();
        $areas = Area::all();
        $shifts = [
            'morning' => ['start' => '06:00', 'end' => '14:00'],
            'afternoon' => ['start' => '14:00', 'end' => '22:00'],
            'night' => ['start' => '22:00', 'end' => '06:00'],
        ];

        foreach ($guards as $i => $guard) {
            $shift = array_keys($shifts)[$i % 3];
            $area = $areas[$i % count($areas)];

            $schedule = Schedule::create([
                'name' => 'Jadwal ' . $guard->full_name,
                'guard_id' => $guard->id,
                'area_id' => $area->id,
                'shift' => $shift,
                'start_time' => $shifts[$shift]['start'],
                'end_time' => $shifts[$shift]['end'],
                'date' => Carbon::today(),
                'notes' => 'Jadwal reguler',
                'is_active' => true,
            ]);

            $checkpoints = Checkpoint::where('area_id', $area->id)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();

            $schedule->checkpoints()->sync(
                $checkpoints->pluck('id')->mapWithKeys(fn($id, $idx) => [$id => ['order' => $idx]])
            );
        }
    }
}
