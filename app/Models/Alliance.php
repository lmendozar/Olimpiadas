<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Alliance extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo_url',
    ];

    /**
     * Get the people in this alliance
     */
    public function people(): HasMany
    {
        return $this->hasMany(Person::class);
    }

    /**
     * Get the matches this alliance participated in
     */
    public function matches(): BelongsToMany
    {
        return $this->belongsToMany(MatchPlay::class, 'match_alliance', 'alliance_id', 'match_id')
                    ->withPivot('position')
                    ->withTimestamps();
    }

    /**
     * Get the competition rankings for this alliance
     */
    public function competitionRankings(): HasMany
    {
        return $this->hasMany(CompetitionRanking::class);
    }

    /**
     * Get total points across all competitions
     */
    public function getTotalPoints(): int
    {
        return $this->competitionRankings()->sum('points_awarded');
    }

    /**
     * Check if alliance is in use (has people or participated in matches)
     */
    public function isInUse(): bool
    {
        return $this->people()->exists() || $this->matches()->exists();
    }

    /**
     * Get competitors only
     */
    public function competitors(): HasMany
    {
        return $this->people()->where('role', 'competidor');
    }
}

