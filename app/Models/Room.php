<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status'];

    protected $casts = [
        'status' => 'integer',
    ];

    // Status constants
    public const STATUS_EMPTY       = 0;
    public const STATUS_IN_USE      = 1;
    public const STATUS_MAINTENANCE = 2;

    /**
     * Scope: rooms that are not in maintenance.
     */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', self::STATUS_MAINTENANCE);
    }

    /**
     * All course sessions assigned to this room.
     */
    public function courseSessions(): HasMany
    {
        return $this->hasMany(CourseSession::class);
    }

    // Convenience helpers
    public function isEmpty(): bool
    {
        return $this->status === self::STATUS_EMPTY;
    }

    public function isInUse(): bool
    {
        return $this->status === self::STATUS_IN_USE;
    }

    public function isMaintenance(): bool
    {
        return $this->status === self::STATUS_MAINTENANCE;
    }
}