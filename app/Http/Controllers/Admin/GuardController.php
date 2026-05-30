<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guard;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class GuardController extends Controller
{
    public function index()
    {
        $guards = Guard::with('user')->latest()->paginate(15);
        return view('admin.guards.index', compact('guards'));
    }

    public function create()
    {
        return view('admin.guards.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'guard_number' => 'required|unique:guards,guard_number',
            'full_name' => 'required|string|max:100',
            'nik' => 'nullable|unique:guards,nik',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'gender' => 'required|in:male,female',
            'birth_date' => 'nullable|date',
            'join_date' => 'required|date',
            'emergency_contact' => 'nullable|string|max:20',
            'emergency_name' => 'nullable|string|max:100',
            'blood_type' => 'nullable|string|max:5',
            'notes' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => UserRole::Guard,
            'phone' => $validated['phone'],
            'is_active' => true,
        ]);

        Guard::create([
            'user_id' => $user->id,
            'guard_number' => $validated['guard_number'],
            'full_name' => $validated['full_name'],
            'nik' => $validated['nik'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'gender' => $validated['gender'],
            'birth_date' => $validated['birth_date'] ?? null,
            'join_date' => $validated['join_date'],
            'emergency_contact' => $validated['emergency_contact'] ?? null,
            'emergency_name' => $validated['emergency_name'] ?? null,
            'blood_type' => $validated['blood_type'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('admin.guards.index')
            ->with('success', 'Guard berhasil ditambahkan.');
    }

    public function show(Guard $guard)
    {
        $guard->load('user', 'patrols.logs', 'schedules', 'attendance');
        return view('admin.guards.show', compact('guard'));
    }

    public function edit(Guard $guard)
    {
        $guard->load('user');
        return view('admin.guards.edit', compact('guard'));
    }

    public function update(Request $request, Guard $guard)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($guard->user_id)],
            'password' => 'nullable|min:8',
            'guard_number' => ['required', Rule::unique('guards')->ignore($guard->id)],
            'full_name' => 'required|string|max:100',
            'nik' => ['nullable', Rule::unique('guards')->ignore($guard->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'gender' => 'required|in:male,female',
            'birth_date' => 'nullable|date',
            'join_date' => 'required|date',
            'emergency_contact' => 'nullable|string|max:20',
            'emergency_name' => 'nullable|string|max:100',
            'blood_type' => 'nullable|string|max:5',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'is_active' => $request->boolean('is_active', true),
        ];
        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }
        $guard->user->update($userData);

        $guard->update([
            'guard_number' => $validated['guard_number'],
            'full_name' => $validated['full_name'],
            'nik' => $validated['nik'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'gender' => $validated['gender'],
            'birth_date' => $validated['birth_date'] ?? null,
            'join_date' => $validated['join_date'],
            'emergency_contact' => $validated['emergency_contact'] ?? null,
            'emergency_name' => $validated['emergency_name'] ?? null,
            'blood_type' => $validated['blood_type'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('admin.guards.index')
            ->with('success', 'Guard berhasil diperbarui.');
    }

    public function destroy(Guard $guard)
    {
        $guard->user->delete();
        $guard->delete();

        return redirect()->route('admin.guards.index')
            ->with('success', 'Guard berhasil dihapus.');
    }
}
