<?php

use Illuminate\Support\Facades\Schedule;
use App\Models\Patrol;
use App\Models\Checkpoint;
use App\Notifications\CheckpointMissed;
use App\Notifications\PatrolLate;
use Illuminate\Support\Facades\Notification;

Schedule::call(function () {
    $latePatrols = Patrol::where('status', 'in_progress')
        ->where('start_time', '<', now()->subHours(2))
        ->get();

    foreach ($latePatrols as $patrol) {
        $patrol->update(['status' => 'missed']);

        $supervisors = \App\Models\User::where('role', 'supervisor')
            ->where('is_active', true)
            ->get();

        try {
            Notification::send($supervisors, new PatrolLate(
                $patrol->guardRel,
                $patrol->start_time->format('H:i')
            ));
        } catch (\Exception $e) {
            report($e);
        }
    }
})->everyThirtyMinutes();

Schedule::call(function () {
    $missedCheckpoints = Checkpoint::where('is_active', true)
        ->whereDoesntHave('patrolLogs', function ($q) {
            $q->whereDate('created_at', today());
        })
        ->get();

    foreach ($missedCheckpoints as $checkpoint) {
        $supervisors = \App\Models\User::where('role', 'supervisor')
            ->where('is_active', true)
            ->get();

        try {
            Notification::send($supervisors, new CheckpointMissed(
                $checkpoint,
                new \App\Models\Guard()
            ));
        } catch (\Exception $e) {
            report($e);
        }
    }
})->dailyAt('23:00');
