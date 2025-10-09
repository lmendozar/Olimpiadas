<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'gender',
        'role',
        'alliance_id',
    ];

    protected $table = 'people';

    /**
     * Get the alliance this person belongs to
     */
    public function alliance(): BelongsTo
    {
        return $this->belongsTo(Alliance::class);
    }

    /**
     * Check if person is in use (has participated in any activity)
     * For now, we'll consider they're in use if they have an alliance
     */
    public function isInUse(): bool
    {
        return $this->alliance_id !== null;
    }

    /**
     * Get formatted gender
     */
    public function getGenderLabel(): string
    {
        return match($this->gender) {
            'masculino' => 'Masculino',
            'femenino' => 'Femenino',
            'otro' => 'Otro',
            default => $this->gender,
        };
    }

    /**
     * Get formatted role
     */
    public function getRoleLabel(): string
    {
        return match($this->role) {
            'competidor' => 'Competidor',
            'organizador' => 'Organizador',
            default => $this->role,
        };
    }
}

