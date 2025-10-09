<?php

namespace App\Exports;

use App\Models\Competition;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CompetitionsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Competition::with(['gameType', 'rankings.alliance'])
            ->orderBy('start_date', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tipo de Juego',
            'Fecha Inicio',
            'Oro (Alianza)',
            'Plata (Alianza)',
            'Bronce (Alianza)',
            'Estado',
        ];
    }

    public function map($competition): array
    {
        $goldAlliance = $competition->rankings->where('position', 1)->first();
        $silverAlliance = $competition->rankings->where('position', 2)->first();
        $bronzeAlliance = $competition->rankings->where('position', 3)->first();

        return [
            $competition->id,
            $competition->gameType->name,
            $competition->start_date->format('Y-m-d'),
            $goldAlliance ? $goldAlliance->alliance->name : '-',
            $silverAlliance ? $silverAlliance->alliance->name : '-',
            $bronzeAlliance ? $bronzeAlliance->alliance->name : '-',
            $competition->is_finalized ? 'Finalizada' : 'En Curso',
        ];
    }
}

