<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alliance;
use App\Models\Competition;
use App\Models\MatchPlay;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    /**
     * Display a listing of matches
     */
    public function index()
    {
        $matches = MatchPlay::with(['competition.gameType', 'winner'])
            ->orderBy('match_date', 'desc')
            ->paginate(15);
        return view('admin.matches.index', compact('matches'));
    }

    /**
     * Show the form for creating a new match
     */
    public function create()
    {
        $competitions = Competition::with('gameType')->orderBy('start_date', 'desc')->get();
        $alliances = Alliance::orderBy('name')->get();
        $people = \App\Models\Person::with('alliance')->where('role', 'competidor')->orderBy('name')->get();
        return view('admin.matches.create', compact('competitions', 'alliances', 'people'));
    }

    /**
     * Store a newly created match
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'competition_id' => 'required|exists:competitions,id',
            'match_date' => 'required|date',
            'alliance_ids' => 'required|array|min:2',
            'alliance_ids.*' => 'exists:alliances,id',
            'participant_ids' => 'nullable|array',
            'participant_ids.*' => 'exists:people,id',
            'photo_urls' => 'nullable|string',
        ]);

        // Process photo gallery
        $photos = [];
        if ($request->filled('photo_urls')) {
            $urls = array_filter(array_map('trim', explode("\n", $validated['photo_urls'])));
            foreach ($urls as $url) {
                if (filter_var($url, FILTER_VALIDATE_URL)) {
                    $photos[] = $url;
                }
            }
        }

        $match = MatchPlay::create([
            'competition_id' => $validated['competition_id'],
            'match_date' => $validated['match_date'],
            'photo_gallery' => !empty($photos) ? $photos : null,
        ]);

        // Attach alliances to the match
        foreach ($validated['alliance_ids'] as $allianceId) {
            $match->alliances()->attach($allianceId);
        }

        // Attach individual participants if specified
        if (!empty($validated['participant_ids'])) {
            foreach ($validated['participant_ids'] as $personId) {
                $person = \App\Models\Person::find($personId);
                if ($person && $person->alliance_id) {
                    $match->participants()->attach($personId, ['alliance_id' => $person->alliance_id]);
                }
            }
        }

        return redirect()->route('admin.matches.index')
            ->with('success', 'Enfrentamiento creado exitosamente.');
    }

    /**
     * Show the form for editing the specified match
     */
    public function edit(MatchPlay $match)
    {
        $competitions = Competition::with('gameType')->orderBy('start_date', 'desc')->get();
        $alliances = Alliance::orderBy('name')->get();
        $people = \App\Models\Person::with('alliance')->where('role', 'competidor')->orderBy('name')->get();
        $match->load(['alliances', 'participants']);
        
        return view('admin.matches.edit', compact('match', 'competitions', 'alliances', 'people'));
    }

    /**
     * Update the specified match
     */
    public function update(Request $request, MatchPlay $match)
    {
        $validated = $request->validate([
            'competition_id' => 'required|exists:competitions,id',
            'match_date' => 'required|date',
            'result_metric' => 'nullable|string|max:255',
            'winner_id' => 'nullable|exists:alliances,id',
            'alliance_ids' => 'required|array|min:2',
            'alliance_ids.*' => 'exists:alliances,id',
            'positions' => 'nullable|array',
            'positions.*' => 'nullable|integer|min:1',
            'participant_ids' => 'nullable|array',
            'participant_ids.*' => 'exists:people,id',
            'photo_urls' => 'nullable|string',
        ]);

        // Process photo gallery
        $photos = [];
        if ($request->filled('photo_urls')) {
            $urls = array_filter(array_map('trim', explode("\n", $validated['photo_urls'])));
            foreach ($urls as $url) {
                if (filter_var($url, FILTER_VALIDATE_URL)) {
                    $photos[] = $url;
                }
            }
        }

        $match->update([
            'competition_id' => $validated['competition_id'],
            'match_date' => $validated['match_date'],
            'result_metric' => $validated['result_metric'],
            'winner_id' => $validated['winner_id'],
            'photo_gallery' => !empty($photos) ? $photos : null,
        ]);

        // Sync alliances with positions (for simultaneous competitions)
        $syncData = [];
        foreach ($validated['alliance_ids'] as $index => $allianceId) {
            $syncData[$allianceId] = [
                'position' => $validated['positions'][$index] ?? null,
            ];
        }
        $match->alliances()->sync($syncData);

        // Sync individual participants if specified
        if (!empty($validated['participant_ids'])) {
            $syncParticipants = [];
            foreach ($validated['participant_ids'] as $personId) {
                $person = \App\Models\Person::find($personId);
                if ($person && $person->alliance_id) {
                    $syncParticipants[$personId] = ['alliance_id' => $person->alliance_id];
                }
            }
            $match->participants()->sync($syncParticipants);
        } else {
            $match->participants()->detach();
        }

        return redirect()->route('admin.matches.index')
            ->with('success', 'Enfrentamiento actualizado exitosamente.');
    }

    /**
     * Finalize the match
     */
    public function finalize(MatchPlay $match)
    {
        if ($match->is_finalized) {
            return back()->with('error', 'El enfrentamiento ya está finalizado.');
        }

        if (!$match->result_metric) {
            return back()->with('error', 'Debe registrar el resultado antes de finalizar.');
        }

        $match->finalize();

        return back()->with('success', 'Enfrentamiento finalizado exitosamente.');
    }

    /**
     * Remove the specified match
     */
    public function destroy(MatchPlay $match)
    {
        $match->delete();

        return redirect()->route('admin.matches.index')
            ->with('success', 'Enfrentamiento eliminado exitosamente.');
    }
}

