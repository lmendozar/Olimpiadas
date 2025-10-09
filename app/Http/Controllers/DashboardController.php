<?php

namespace App\Http\Controllers;

use App\Models\Alliance;
use App\Models\Competition;
use App\Models\MatchPlay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show public dashboard
     */
    public function index()
    {
        // Get overall rankings
        $rankings = Alliance::with('competitionRankings.competition.gameType')
            ->get()
            ->map(function ($alliance) {
                return [
                    'alliance' => $alliance,
                    'total_points' => $alliance->getTotalPoints(),
                    'gold_medals' => $alliance->competitionRankings()->where('position', 1)->count(),
                    'silver_medals' => $alliance->competitionRankings()->where('position', 2)->count(),
                    'bronze_medals' => $alliance->competitionRankings()->where('position', 3)->count(),
                ];
            })
            ->sortByDesc('total_points')
            ->values();

        // Get recent and upcoming matches
        $recentMatches = MatchPlay::with(['competition.gameType', 'alliances', 'winner'])
            ->where('is_finalized', true)
            ->orderBy('match_date', 'desc')
            ->take(10)
            ->get();

        $upcomingMatches = MatchPlay::with(['competition.gameType', 'alliances'])
            ->where('is_finalized', false)
            ->orderBy('match_date', 'asc')
            ->take(10)
            ->get();

        return view('dashboard', compact('rankings', 'recentMatches', 'upcomingMatches'));
    }

    /**
     * Show match details
     */
    public function showMatch(MatchPlay $matchRecord)
    {
        $matchRecord->load(['competition.gameType', 'alliances.people', 'winner', 'participants.alliance']);
        
        return view('match-detail', compact('matchRecord'));
    }
}

