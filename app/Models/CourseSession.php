<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseSession extends Model
{
    use HasFactory;

    protected $table = 'course_sessions'; // unified — replaces old course_sessions + courses_sessions

    protected $fillable = [
        'class_id',
        'teacher_id',
        'title',
        'session_date',
        'start_time',
        'duration',
        'room_id',
        'type',
        'status',
    ];

    protected $casts = [
        'session_date' => 'date',
        'duration'    => 'integer',
        'type'        => 'integer',
        'status'      => 'integer',
    ];

    // Type constants
    public const TYPE_REGULAR  = 0;
    public const TYPE_EXAM     = 1;
    public const TYPE_PRACTICE = 2;

    // Status constants
    public const STATUS_SCHEDULED   = 0;
    public const STATUS_IN_PROGRESS = 1;
    public const STATUS_COMPLETED   = 2;
    public const STATUS_CANCELLED   = 3;

    /**
     * The class this session belongs to.
     */
    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    /**
     * The teacher for this session.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * The physical room for this session.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Computed end time based on start_time + duration.
     *
     * @return Carbon
     */
    public function getEndTimeAttribute(): Carbon
    {
        return $this->session_date->copy()->setTimeFromTimeString($this->start_time)
               ->addMinutes($this->duration);
    }

    /**
     * Determines if the session is currently active.
     * Session must be status=SCHEDULED and current time is between start and end.
     *
     * @return bool
     */
    public function isCurrentlyActive(): bool
    {
        if ($this->status !== self::STATUS_SCHEDULED) {
            return false;
        }

        $now = now();
        return $now->between(
            $this->session_date->copy()->setTimeFromTimeString($this->start_time),
            $this->end_time
        );
    }
}