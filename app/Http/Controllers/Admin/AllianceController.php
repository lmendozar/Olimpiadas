<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alliance;
use Illuminate\Http\Request;

class AllianceController extends Controller
{
    /**
     * Display a listing of alliances
     */
    public function index()
    {
        $alliances = Alliance::withCount('people')->paginate(15);
        return view('admin.alliances.index', compact('alliances'));
    }

    /**
     * Show the form for creating a new alliance
     */
    public function create()
    {
        return view('admin.alliances.create');
    }

    /**
     * Store a newly created alliance
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:alliances',
            'logo_url' => 'nullable|url|max:500',
        ]);

        Alliance::create($validated);

        return redirect()->route('admin.alliances.index')
            ->with('success', 'Alianza creada exitosamente.');
    }

    /**
     * Display the specified alliance
     */
    public function show(Alliance $alliance)
    {
        $alliance->load(['people', 'competitionRankings.competition.gameType']);
        return view('admin.alliances.show', compact('alliance'));
    }

    /**
     * Show the form for editing the specified alliance
     */
    public function edit(Alliance $alliance)
    {
        return view('admin.alliances.edit', compact('alliance'));
    }

    /**
     * Update the specified alliance
     */
    public function update(Request $request, Alliance $alliance)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:alliances,name,' . $alliance->id,
            'logo_url' => 'nullable|url|max:500',
        ]);

        $alliance->update($validated);

        return redirect()->route('admin.alliances.index')
            ->with('success', 'Alianza actualizada exitosamente.');
    }

    /**
     * Remove the specified alliance
     */
    public function destroy(Alliance $alliance)
    {
        if ($alliance->isInUse()) {
            return back()->with('error', 'No se puede eliminar la alianza porque está en uso.');
        }

        $alliance->delete();

        return redirect()->route('admin.alliances.index')
            ->with('success', 'Alianza eliminada exitosamente.');
    }
}

