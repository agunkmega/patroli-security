<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Guard;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->isGuard()) {
            $guard = $user->guardProfile;
            $attendances = Attendance::where('guard_id', $guard->id)
                ->latest()
                ->paginate(15);
            $todayAttendance = Attendance::where('guard_id', $guard->id)
                ->whereDate('date', today())
                ->first();
            return view('attendance.index', compact('attendances', 'todayAttendance'));
        }

        $attendances = Attendance::with('guardRel.user')
            ->latest()
            ->paginate(15);
        $todayAttendance = null;
        return view('attendance.index', compact('attendances', 'todayAttendance'));
    }

    public function checkIn(Request $request)
    {
        $user = Auth::user();
        $guard = $user->guardProfile;

        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'nullable|image|max:5120',
        ]);

        $existing = Attendance::where('guard_id', $guard->id)
            ->whereDate('date', today())
            ->first();

        if ($existing) {
            return back()->with('info', 'Anda sudah check-in hari ini.');
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('attendance-photos', 'public');
        }

        $schedule = \App\Models\Schedule::where(function ($q) use ($guard) {
                $q->where('guard_id', $guard->id)->orWhereNull('guard_id');
            })
            ->where(function ($q) {
                $q->whereDate('date', today())->orWhereNull('date');
            })
            ->first();

        $isLate = false;
        if ($schedule && $schedule->start_time) {
            $scheduledStart = Carbon::parse($schedule->start_time->format('H:i'));
            $checkInTime = now();
            $isLate = $checkInTime->gt($scheduledStart->copy()->addMinutes(15));
        }

        Attendance::create([
            'guard_id' => $guard->id,
            'date' => today(),
            'check_in_time' => now(),
            'check_in_lat' => $validated['latitude'],
            'check_in_lng' => $validated['longitude'],
            'check_in_photo' => $photoPath,
            'status' => $isLate ? 'late' : 'present',
        ]);

        return back()->with('success', 'Check-in berhasil!');
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();
        $guard = $user->guardProfile;

        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'nullable|image|max:5120',
        ]);

        $attendance = Attendance::where('guard_id', $guard->id)
            ->whereDate('date', today())
            ->first();

        if (!$attendance) {
            return back()->with('error', 'Anda belum check-in.');
        }

        if ($attendance->check_out_time) {
            return back()->with('info', 'Anda sudah check-out hari ini.');
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('attendance-photos', 'public');
        }

        $attendance->update([
            'check_out_time' => now(),
            'check_out_lat' => $validated['latitude'],
            'check_out_lng' => $validated['longitude'],
            'check_out_photo' => $photoPath,
        ]);

        return back()->with('success', 'Check-out berhasil!');
    }
}
