<?php

namespace App\Exports;

use App\Models\MatchPlay;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MatchesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return MatchPlay::with(['competition.gameType', 'alliances', 'winner'])
            ->orderBy('match_date', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Competencia',
            'Fecha/Hora',
            'Participantes',
            'Resultado',
            'Ganador',
            'Estado',
        ];
    }

    public function map($matchRecord): array
    {
        return [
            $matchRecord->id,
            $matchRecord->competition->gameType->name,
            $matchRecord->match_date->format('Y-m-d H:i'),
            $matchRecord->alliances->pluck('name')->join(' vs '),
            $matchRecord->result_metric ?? '-',
            $matchRecord->winner ? $matchRecord->winner->name : ($matchRecord->result_metric ? 'Empate' : '-'),
            $matchRecord->is_finalized ? 'Finalizado' : 'Pendiente',
        ];
    }
}

