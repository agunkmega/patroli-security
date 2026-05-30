<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmergencyReport;
use App\Models\Patrol;
use App\Notifications\EmergencyAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class EmergencyController extends Controller
{
    public function sendSOS(Request $request)
    {
        $user = Auth::user();
        $guard = $user->guardProfile;

        $validated = $request->validate([
            'type' => 'required|in:sos,fire,theft,accident,medical,other',
            'description' => 'required|string|max:1000',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'nullable|image|max:10240',
        ]);

        $patrol = Patrol::where('guard_id', $guard->id)
            ->where('status', 'in_progress')
            ->first();

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('emergency-photos', 'public');
        }

        $report = EmergencyReport::create([
            'report_number' => 'EMG-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6)),
            'guard_id' => $guard->id,
            'patrol_id' => $patrol?->id,
            'type' => $validated['type'],
            'description' => $validated['description'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'photo' => $photoPath,
            'status' => 'pending',
        ]);

        $supervisors = \App\Models\User::where('role', 'supervisor')
            ->where('is_active', true)
            ->get();
        try {
            Notification::send($supervisors, new EmergencyAlert($report));
        } catch (\Exception $e) {
            report($e);
        }

        return response()->json([
            'success' => true,
            'message' => 'SOS berhasil dikirim! Bantuan akan segera datang.',
            'data' => $report,
        ]);
    }
}
