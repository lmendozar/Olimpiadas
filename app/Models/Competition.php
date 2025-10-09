<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Competition extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_type_id',
        'start_date',
        'first_place_points',
        'second_place_points',
        'third_place_points',
        'is_simultaneous',
        'is_finalized',
    ];

    protected $casts = [
        'start_date' => 'date',
        'is_simultaneous' => 'boolean',
        'is_finalized' => 'boolean',
    ];

    /**
     * Get the game type for this competition
     */
    public function gameType(): BelongsTo
    {
        return $this->belongsTo(GameType::class);
    }

    /**
     * Get the matches in this competition
     */
    public function matches(): HasMany
    {
        return $this->hasMany(MatchPlay::class);
    }

    /**
     * Get the rankings for this competition
     */
    public function rankings(): HasMany
    {
        return $this->hasMany(CompetitionRanking::class);
    }

    /**
     * Calculate and save rankings based on match results
     */
    public function calculateRankings(): void
    {
        if ($this->is_simultaneous) {
            // Para competencias simultáneas (ej: natación)
            // Los lugares se determinan directamente de los enfrentamientos
            $this->calculateSimultaneousRankings();
        } else {
            // Para competencias regulares
            // Los lugares se determinan por suma de victorias
            $this->calculateRegularRankings();
        }
        
        $this->update(['is_finalized' => true]);
    }

    /**
     * Calculate rankings for simultaneous competitions
     */
    private function calculateSimultaneousRankings(): void
    {
        // Get the main match with positions
        $matchRecord = $this->matches()->where('is_finalized', true)->first();
        
        if (!$matchRecord) {
            return;
        }

        $alliances = $matchRecord->alliances()->wherePivot('position', '!=', null)->get();
        
        foreach ($alliances as $alliance) {
            $position = $alliance->pivot->position;
            
            if ($position > 3) {
                continue;
            }

            $points = match($position) {
                1 => $this->first_place_points,
                2 => $this->second_place_points,
                3 => $this->third_place_points,
                default => 0,
            };

            CompetitionRanking::updateOrCreate(
                [
                    'competition_id' => $this->id,
                    'alliance_id' => $alliance->id,
                ],
                [
                    'position' => $position,
                    'points_awarded' => $points,
                ]
            );
        }
    }

    /**
     * Calculate rankings for regular competitions
     */
    private function calculateRegularRankings(): void
    {
        // Count wins for each alliance
        $wins = [];
        
        foreach ($this->matches()->where('is_finalized', true)->get() as $matchRecord) {
            if ($matchRecord->winner_id) {
                $wins[$matchRecord->winner_id] = ($wins[$matchRecord->winner_id] ?? 0) + 1;
            }
        }

        // Sort by wins descending
        arsort($wins);
        
        $position = 1;
        foreach (array_slice($wins, 0, 3, true) as $allianceId => $winCount) {
            $points = match($position) {
                1 => $this->first_place_points,
                2 => $this->second_place_points,
                3 => $this->third_place_points,
                default => 0,
            };

            CompetitionRanking::updateOrCreate(
                [
                    'competition_id' => $this->id,
                    'alliance_id' => $allianceId,
                ],
                [
                    'position' => $position,
                    'points_awarded' => $points,
                ]
            );
            
            $position++;
        }
    }
}

