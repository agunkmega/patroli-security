<?php

namespace App\Http\Controllers;

use App\Models\EmergencyReport;
use App\Models\Patrol;
use App\Models\User;
use App\Notifications\EmergencyAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class EmergencyController extends Controller
{
    public function index()
    {
        $reports = EmergencyReport::with('guardRel.user', 'responder')
            ->latest()
            ->paginate(15);
        return view('emergency.index', compact('reports'));
    }

    public function create()
    {
        return view('emergency.panic');
    }

    public function store(Request $request)
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

        $supervisors = User::where('role', 'supervisor')
            ->where('is_active', true)
            ->get();
        try {
            Notification::send($supervisors, new EmergencyAlert($report));
        } catch (\Exception $e) {
            report($e);
        }

        return redirect()->back()->with('success', 'Laporan darurat berhasil dikirim!');
    }

    public function respond(Request $request, EmergencyReport $report)
    {
        $report->update([
            'status' => 'responded',
            'responded_at' => now(),
            'responded_by' => Auth::id(),
            'response_notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Emergency telah direspon.');
    }

    public function resolve(EmergencyReport $report)
    {
        $report->update([
            'status' => 'resolved',
            'responded_at' => now(),
            'responded_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Emergency telah diselesaikan.');
    }
}
