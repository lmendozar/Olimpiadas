<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\GameType;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    /**
     * Display a listing of competitions
     */
    public function index()
    {
        $competitions = Competition::with('gameType')
            ->withCount('matches')
            ->orderBy('start_date', 'desc')
            ->paginate(15);
        return view('admin.competitions.index', compact('competitions'));
    }

    /**
     * Show the form for creating a new competition
     */
    public function create()
    {
        $gameTypes = GameType::orderBy('name')->get();
        return view('admin.competitions.create', compact('gameTypes'));
    }

    /**
     * Store a newly created competition
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_type_id' => 'required|exists:game_types,id',
            'start_date' => 'required|date',
            'first_place_points' => 'required|integer|min:0',
            'second_place_points' => 'required|integer|min:0',
            'third_place_points' => 'required|integer|min:0',
            'is_simultaneous' => 'boolean',
        ]);

        $validated['is_simultaneous'] = $request->boolean('is_simultaneous');

        Competition::create($validated);

        return redirect()->route('admin.competitions.index')
            ->with('success', 'Competencia creada exitosamente.');
    }

    /**
     * Display the specified competition
     */
    public function show(Competition $competition)
    {
        $competition->load(['gameType', 'matches.alliances', 'rankings.alliance']);
        return view('admin.competitions.show', compact('competition'));
    }

    /**
     * Show the form for editing the specified competition
     */
    public function edit(Competition $competition)
    {
        $gameTypes = GameType::orderBy('name')->get();
        return view('admin.competitions.edit', compact('competition', 'gameTypes'));
    }

    /**
     * Update the specified competition
     */
    public function update(Request $request, Competition $competition)
    {
        $validated = $request->validate([
            'game_type_id' => 'required|exists:game_types,id',
            'start_date' => 'required|date',
            'first_place_points' => 'required|integer|min:0',
            'second_place_points' => 'required|integer|min:0',
            'third_place_points' => 'required|integer|min:0',
            'is_simultaneous' => 'boolean',
        ]);

        $validated['is_simultaneous'] = $request->boolean('is_simultaneous');

        $competition->update($validated);

        return redirect()->route('admin.competitions.index')
            ->with('success', 'Competencia actualizada exitosamente.');
    }

    /**
     * Finalize the competition and calculate rankings
     */
    public function finalize(Competition $competition)
    {
        if ($competition->is_finalized) {
            return back()->with('error', 'La competencia ya está finalizada.');
        }

        $competition->calculateRankings();

        return back()->with('success', 'Competencia finalizada y rankings calculados.');
    }

    /**
     * Remove the specified competition
     */
    public function destroy(Competition $competition)
    {
        $competition->delete();

        return redirect()->route('admin.competitions.index')
            ->with('success', 'Competencia eliminada exitosamente.');
    }
}

