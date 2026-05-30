<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::withCount('checkpoints', 'activeCheckpoints')->latest()->paginate(15);
        return view('admin.areas.index', compact('areas'));
    }

    public function create()
    {
        return view('admin.areas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:areas,code|max:20',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'radius' => 'nullable|numeric|min:1',
            'is_active' => 'boolean',
        ]);

        Area::create($validated);

        return redirect()->route('admin.areas.index')
            ->with('success', 'Area berhasil ditambahkan.');
    }

    public function edit(Area $area)
    {
        return view('admin.areas.edit', compact('area'));
    }

    public function update(Request $request, Area $area)
    {
        $validated = $request->validate([
            'code' => ['required', 'max:20', Rule::unique('areas')->ignore($area->id)],
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'radius' => 'nullable|numeric|min:1',
            'is_active' => 'boolean',
        ]);

        $area->update($validated);

        return redirect()->route('admin.areas.index')
            ->with('success', 'Area berhasil diperbarui.');
    }

    public function destroy(Area $area)
    {
        if ($area->checkpoints()->count() > 0) {
            return back()->with('error', 'Area tidak dapat dihapus karena masih memiliki checkpoint.');
        }
        $area->delete();
        return redirect()->route('admin.areas.index')
            ->with('success', 'Area berhasil dihapus.');
    }
}
