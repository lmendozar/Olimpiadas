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
        
        // Sort photo gallery by filename datetime if it exists
        if ($matchRecord->photo_gallery && count($matchRecord->photo_gallery) > 0) {
            $sortedGallery = $matchRecord->photo_gallery;
            usort($sortedGallery, function($a, $b) {
                $dateA = $this->extractDateTimeFromFilename($a);
                $dateB = $this->extractDateTimeFromFilename($b);
                
                if ($dateA && $dateB) {
                    return $dateA <=> $dateB; // Ascending order (oldest first)
                }
                
                if ($dateA && !$dateB) return -1;
                if (!$dateA && $dateB) return 1;
                
                return 0;
            });
            $matchRecord->photo_gallery = $sortedGallery;
        }
        
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
            foreach ($match->photo_gallery as $item) {
                $galleryItems[] = [
                    'media' => $item,
                    'type' => $this->detectMediaType($item),
                    'match_date' => $match->match_date->format('d/m/Y H:i'),
                    'game_type' => $match->competition->gameType->name,
                    'result' => $match->result_metric ?? 'Sin resultado',
                    'alliances' => $match->alliances->pluck('name')->join(' vs '),
                    'match_id' => $match->id,
                    'match_timestamp' => $match->match_date->timestamp, // Add timestamp for sorting
                ];
            }
        }

        // Get event gallery photos from system settings
        $eventGallery = \App\Models\SystemSetting::where('key', 'event_gallery')->first();
        if ($eventGallery) {
            $eventPhotos = json_decode($eventGallery->value, true) ?? [];
            foreach ($eventPhotos as $item) {
                $galleryItems[] = [
                    'media' => $item,
                    'type' => $this->detectMediaType($item),
                    'match_date' => 'Evento',
                    'game_type' => 'Eventos de las Olimpiadas',
                    'result' => 'General',
                    'alliances' => 'Todos los participantes',
                    'match_id' => null,
                    'match_timestamp' => null, // Event photos have no match timestamp
                ];
            }
        }

        // Sort by filename datetime globally (format: yyyyMMdd HHmmss_xxxxx.ext)
        usort($galleryItems, function($a, $b) {
            $dateA = $this->extractDateTimeFromFilename($a['media']);
            $dateB = $this->extractDateTimeFromFilename($b['media']);
            
            // If both have valid dates from filename, compare them
            if ($dateA && $dateB) {
                return $dateB <=> $dateA; // Descending order (newest first)
            }
            
            // If only one has a date from filename, prioritize it
            if ($dateA && !$dateB) return -1;
            if (!$dateA && $dateB) return 1;
            
            // If neither has a date in filename, fall back to match date timestamp
            if (!$dateA && !$dateB) {
                $matchDateA = $a['match_timestamp'] ?? null;
                $matchDateB = $b['match_timestamp'] ?? null;
                
                if ($matchDateA && $matchDateB) {
                    return $matchDateB <=> $matchDateA; // Descending order
                }
                
                // If only one has match timestamp, prioritize it
                if ($matchDateA && !$matchDateB) return -1;
                if (!$matchDateA && $matchDateB) return 1;
            }
            
            return 0;
        });

        return view('photo-gallery', compact('galleryItems'));
    }

    /**
     * Detect media type (image or video) from URL
     */
    private function detectMediaType($url): string
    {
        // Video extensions
        $videoExtensions = ['mp4', 'webm', 'ogg', 'mov', 'avi'];
        
        // YouTube patterns
        if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $url, $matches) ||
            preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return 'youtube';
        }
        
        // Vimeo patterns
        if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
            return 'vimeo';
        }
        
        // Check file extension
        $extension = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
        
        if (in_array($extension, $videoExtensions)) {
            return 'video';
        }
        
        return 'image';
    }

    /**
     * Extract datetime from filename format: yyyyMMdd HHmmss_xxxxx.ext
     * Example: 20251022_181425037_Zumba.jpg
     */
    private function extractDateTimeFromFilename($url): ?int
    {
        // Get filename from URL
        $filename = basename(parse_url($url, PHP_URL_PATH));
        
        // Try to match pattern: yyyyMMdd_HHmmssSSS_xxxxx.ext
        // The pattern is: 8 digits for date, underscore, time digits, underscore, rest
        if (preg_match('/^(\d{8})_(\d{6,9})_/', $filename, $matches)) {
            $dateStr = $matches[1]; // 20251022
            $timeStr = substr($matches[2], 0, 6); // Take only HHmmss (first 6 digits)
            
            // Parse date and time
            $year = substr($dateStr, 0, 4);
            $month = substr($dateStr, 4, 2);
            $day = substr($dateStr, 6, 2);
            $hour = substr($timeStr, 0, 2);
            $minute = substr($timeStr, 2, 2);
            $second = substr($timeStr, 4, 2);
            
            // Create timestamp
            try {
                $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
                return $timestamp ?: null;
            } catch (\Exception $e) {
                return null;
            }
        }
        
        return null;
    }
}

