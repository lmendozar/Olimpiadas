<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameType;
use Illuminate\Http\Request;

class GameTypeController extends Controller
{
    /**
     * Display a listing of game types
     */
    public function index()
    {
        $gameTypes = GameType::withCount('competitions')->paginate(15);
        return view('admin.game-types.index', compact('gameTypes'));
    }

    /**
     * Show the form for creating a new game type
     */
    public function create()
    {
        return view('admin.game-types.create');
    }

    /**
     * Store a newly created game type
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:game_types',
            'result_metric' => 'required|in:tiempo,goles,sets,contador',
            'requires_individual_participants' => 'boolean',
        ]);

        $validated['requires_individual_participants'] = $request->boolean('requires_individual_participants');

        GameType::create($validated);

        return redirect()->route('admin.game-types.index')
            ->with('success', 'Tipo de juego creado exitosamente.');
    }

    /**
     * Show the form for editing the specified game type
     */
    public function edit(GameType $gameType)
    {
        return view('admin.game-types.edit', compact('gameType'));
    }

    /**
     * Update the specified game type
     */
    public function update(Request $request, GameType $gameType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:game_types,name,' . $gameType->id,
            'result_metric' => 'required|in:tiempo,goles,sets,contador',
            'requires_individual_participants' => 'boolean',
        ]);

        $validated['requires_individual_participants'] = $request->boolean('requires_individual_participants');

        $gameType->update($validated);

        return redirect()->route('admin.game-types.index')
            ->with('success', 'Tipo de juego actualizado exitosamente.');
    }

    /**
     * Remove the specified game type
     */
    public function destroy(GameType $gameType)
    {
        if ($gameType->competitions()->exists()) {
            return back()->with('error', 'No se puede eliminar el tipo de juego porque está en uso.');
        }

        $gameType->delete();

        return redirect()->route('admin.game-types.index')
            ->with('success', 'Tipo de juego eliminado exitosamente.');
    }
}

