<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alliance;
use App\Models\Competition;
use App\Models\MatchPlay;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RankingsExport;
use App\Exports\CompetitionsExport;
use App\Exports\MatchesExport;

class ExportController extends Controller
{
    /**
     * Export overall rankings to Excel
     */
    public function exportRankings()
    {
        return Excel::download(new RankingsExport, 'clasificacion-general-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export competitions to Excel
     */
    public function exportCompetitions()
    {
        return Excel::download(new CompetitionsExport, 'competencias-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export matches to Excel
     */
    public function exportMatches()
    {
        return Excel::download(new MatchesExport, 'enfrentamientos-' . date('Y-m-d') . '.xlsx');
    }
}

