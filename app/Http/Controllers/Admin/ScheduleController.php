<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Checkpoint;
use App\Models\Guard;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with('guardRel.user', 'area', 'checkpoints')
            ->latest()
            ->paginate(15);
        return view('admin.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $guards = Guard::where('is_active', true)->with('user')->get();
        $areas = Area::where('is_active', true)->get();
        $checkpoints = Checkpoint::where('is_active', true)->get();
        return view('admin.schedules.create', compact('guards', 'areas', 'checkpoints'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'guard_id' => 'nullable|exists:guards,id',
            'area_id' => 'nullable|exists:areas,id',
            'shift' => 'required|in:morning,afternoon,night',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'is_daily' => 'boolean',
            'date' => 'nullable|date',
            'checkpoints' => 'nullable|array',
            'checkpoints.*' => 'exists:checkpoints,id',
            'notes' => 'nullable|string',
        ]);

        $schedule = Schedule::create([
            'name' => $validated['name'],
            'guard_id' => $validated['guard_id'] ?? null,
            'area_id' => $validated['area_id'],
            'shift' => $validated['shift'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'date' => $request->boolean('is_daily') ? null : ($validated['date'] ?? now()),
            'notes' => $validated['notes'] ?? null,
            'is_active' => true,
        ]);

        if (!empty($validated['checkpoints'])) {
            $checkpointData = [];
            foreach ($validated['checkpoints'] as $order => $checkpointId) {
                $checkpointData[$checkpointId] = ['order' => $order];
            }
            $schedule->checkpoints()->sync($checkpointData);
        }

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil dibuat.');
    }

    public function edit(Schedule $schedule)
    {
        $guards = Guard::where('is_active', true)->with('user')->get();
        $areas = Area::where('is_active', true)->get();
        $checkpoints = Checkpoint::where('is_active', true)->get();
        $schedule->load('checkpoints');
        return view('admin.schedules.edit', compact('schedule', 'guards', 'areas', 'checkpoints'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'guard_id' => 'nullable|exists:guards,id',
            'area_id' => 'nullable|exists:areas,id',
            'shift' => 'required|in:morning,afternoon,night',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'is_daily' => 'boolean',
            'date' => 'nullable|date',
            'checkpoints' => 'nullable|array',
            'checkpoints.*' => 'exists:checkpoints,id',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $schedule->update([
            'name' => $validated['name'],
            'guard_id' => $validated['guard_id'],
            'area_id' => $validated['area_id'],
            'shift' => $validated['shift'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'date' => $request->boolean('is_daily') ? null : ($validated['date'] ?? $schedule->date),
            'notes' => $validated['notes'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->has('checkpoints')) {
            $checkpointData = [];
            foreach ($validated['checkpoints'] as $order => $checkpointId) {
                $checkpointData[$checkpointId] = ['order' => $order];
            }
            $schedule->checkpoints()->sync($checkpointData);
        }

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->checkpoints()->detach();
        $schedule->delete();

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }
}
