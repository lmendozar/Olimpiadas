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

    /**
     * Show photo gallery with all match photos
     */
    public function photoGallery()
    {
        // Get all matches with photos, order by date
        $matches = MatchPlay::with(['competition.gameType', 'alliances', 'winner'])
            ->whereNotNull('photo_gallery')
            ->whereJsonLength('photo_gallery', '>', 0)
            ->orderBy('match_date', 'desc')
            ->get();

        // Prepare gallery items with all photos and their match info
        $galleryItems = [];
        foreach ($matches as $match) {
            foreach ($match->photo_gallery as $photo) {
                $galleryItems[] = [
                    'photo' => $photo,
                    'match_date' => $match->match_date->format('d/m/Y H:i'),
                    'game_type' => $match->competition->gameType->name,
                    'result' => $match->result_metric ?? 'Sin resultado',
                    'alliances' => $match->alliances->pluck('name')->join(' vs '),
                    'match_id' => $match->id,
                ];
            }
        }

        // Get event gallery photos from system settings
        $eventGallery = \App\Models\SystemSetting::where('key', 'event_gallery')->first();
        if ($eventGallery) {
            $eventPhotos = json_decode($eventGallery->value, true) ?? [];
            foreach ($eventPhotos as $photo) {
                $galleryItems[] = [
                    'photo' => $photo,
                    'match_date' => 'Evento',
                    'game_type' => 'Eventos de las Olimpiadas',
                    'result' => 'General',
                    'alliances' => 'Todos los participantes',
                    'match_id' => null,
                ];
            }
        }

        // Shuffle to mix match photos with event photos
        shuffle($galleryItems);

        return view('photo-gallery', compact('galleryItems'));
    }
}

