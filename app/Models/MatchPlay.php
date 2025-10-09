<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MatchPlay extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'competition_id',
        'match_date',
        'result_metric',
        'winner_id',
        'is_finalized',
        'photo_gallery',
    ];

    protected $casts = [
        'match_date' => 'datetime',
        'is_finalized' => 'boolean',
        'photo_gallery' => 'array',
    ];

    /**
     * Get the competition this match belongs to
     */
    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    /**
     * Get the winner alliance
     */
    public function winner(): BelongsTo
    {
        return $this->belongsTo(Alliance::class, 'winner_id');
    }

    /**
     * Get the alliances participating in this match
     */
    public function alliances(): BelongsToMany
    {
        return $this->belongsToMany(Alliance::class, 'match_alliance', 'match_id', 'alliance_id')
                    ->withPivot('position')
                    ->withTimestamps();
    }

    /**
     * Get the individual participants (people) in this match
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'match_person', 'match_id', 'person_id')
                    ->withPivot('alliance_id')
                    ->withTimestamps();
    }

    /**
     * Finalize the match and update competition if needed
     */
    public function finalize(): void
    {
        $this->update(['is_finalized' => true]);
        
        // Check if all matches in the competition are finalized
        $competition = $this->competition;
        $allFinalized = $competition->matches()->where('is_finalized', false)->count() === 0;
        
        if ($allFinalized && !$competition->is_finalized) {
            $competition->calculateRankings();
        }
    }
}

