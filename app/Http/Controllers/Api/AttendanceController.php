<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Guard;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $user = Auth::user();
        $guard = $user->guardProfile;

        $attendance = Attendance::where('guard_id', $guard->id)
            ->latest('date')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $attendance,
        ]);
    }

    public function today()
    {
        $user = Auth::user();
        $guard = $user->guardProfile;

        $today = Attendance::where('guard_id', $guard->id)
            ->whereDate('date', today())
            ->first();

        return response()->json([
            'success' => true,
            'data' => $today,
        ]);
    }

    public function checkIn(Request $request)
    {
        $user = Auth::user();
        $guard = $user->guardProfile;

        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'nullable|image|max:5120',
            'notes' => 'nullable|string|max:500',
        ]);

        $existing = Attendance::where('guard_id', $guard->id)
            ->whereDate('date', today())
            ->first();

        if ($existing && $existing->check_in_time) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah check-in hari ini.',
                'data' => $existing,
            ], 422);
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('attendance', 'public');
        }

        if ($existing) {
            $existing->update([
                'check_in_time' => now(),
                'check_in_lat' => $validated['latitude'],
                'check_in_lng' => $validated['longitude'],
                'check_in_photo' => $photoPath,
                'notes' => $validated['notes'] ?? null,
                'status' => 'present',
            ]);
            $attendance = $existing;
        } else {
            $attendance = Attendance::create([
                'guard_id' => $guard->id,
                'date' => today(),
                'check_in_time' => now(),
                'check_in_lat' => $validated['latitude'],
                'check_in_lng' => $validated['longitude'],
                'check_in_photo' => $photoPath,
                'notes' => $validated['notes'] ?? null,
                'status' => 'present',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil',
            'data' => $attendance,
        ]);
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();
        $guard = $user->guardProfile;

        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'nullable|image|max:5120',
            'notes' => 'nullable|string|max:500',
        ]);

        $attendance = Attendance::where('guard_id', $guard->id)
            ->whereDate('date', today())
            ->first();

        if (!$attendance || !$attendance->check_in_time) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum check-in hari ini.',
            ], 422);
        }

        if ($attendance->check_out_time) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah check-out hari ini.',
                'data' => $attendance,
            ], 422);
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('attendance', 'public');
        }

        $attendance->update([
            'check_out_time' => now(),
            'check_out_lat' => $validated['latitude'],
            'check_out_lng' => $validated['longitude'],
            'check_out_photo' => $photoPath,
            'notes' => $validated['notes'] ?? $attendance->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-out berhasil',
            'data' => $attendance,
        ]);
    }
}
