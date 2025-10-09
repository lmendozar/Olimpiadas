<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitionRanking extends Model
{
    use HasFactory;

    protected $fillable = [
        'competition_id',
        'alliance_id',
        'position',
        'points_awarded',
    ];

    /**
     * Get the competition
     */
    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    /**
     * Get the alliance
     */
    public function alliance(): BelongsTo
    {
        return $this->belongsTo(Alliance::class);
    }

    /**
     * Get medal type based on position
     */
    public function getMedalType(): string
    {
        return match($this->position) {
            1 => 'Oro',
            2 => 'Plata',
            3 => 'Bronce',
            default => 'N/A',
        };
    }

    /**
     * Get medal color class for UI
     */
    public function getMedalColorClass(): string
    {
        return match($this->position) {
            1 => 'text-yellow-500',
            2 => 'text-gray-400',
            3 => 'text-orange-600',
            default => 'text-gray-300',
        };
    }
}

