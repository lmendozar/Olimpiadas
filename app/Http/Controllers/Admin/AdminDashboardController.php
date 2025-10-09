<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alliance;
use App\Models\Competition;
use App\Models\GameType;
use App\Models\MatchPlay;
use App\Models\Person;
use App\Models\User;

class AdminDashboardController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_organizers' => User::where('role', 'organizador')->count(),
            'total_game_types' => GameType::count(),
            'total_alliances' => Alliance::count(),
            'total_people' => Person::count(),
            'total_competitions' => Competition::count(),
            'total_matches' => MatchPlay::count(),
            'finalized_competitions' => Competition::where('is_finalized', true)->count(),
            'pending_matches' => MatchPlay::where('is_finalized', false)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}

