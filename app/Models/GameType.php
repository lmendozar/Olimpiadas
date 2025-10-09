<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'result_metric',
        'requires_individual_participants',
    ];

    protected $casts = [
        'requires_individual_participants' => 'boolean',
    ];

    /**
     * Get the competitions for this game type
     */
    public function competitions(): HasMany
    {
        return $this->hasMany(Competition::class);
    }

    /**
     * Get formatted metric label
     */
    public function getMetricLabel(): string
    {
        return match($this->result_metric) {
            'tiempo' => 'Tiempo (s)',
            'goles' => 'Goles',
            'sets' => 'Sets',
            'contador' => 'Contador',
            default => $this->result_metric,
        };
    }
}

