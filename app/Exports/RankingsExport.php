<?php

namespace App\Exports;

use App\Models\Alliance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RankingsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Alliance::with('competitionRankings')
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
    }

    public function headings(): array
    {
        return [
            'Posición',
            'Alianza/País',
            'Oro',
            'Plata',
            'Bronce',
            'Puntos Totales',
        ];
    }

    public function map($row): array
    {
        static $position = 0;
        $position++;

        return [
            $position,
            $row['alliance']->name,
            $row['gold_medals'],
            $row['silver_medals'],
            $row['bronze_medals'],
            $row['total_points'],
        ];
    }
}

