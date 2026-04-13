<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'type',
        'old_room_id',
        'old_session_date',
        'old_start_time',
        'new_room_id',
        'new_session_date',
        'new_start_time',
        'new_duration',
        'reason',
        'status',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'type'             => 'integer',
        'status'           => 'integer',
        'old_session_date' => 'date',
        'new_session_date' => 'date',
        'reviewed_at'      => 'datetime',
    ];

    // Type constants
    public const TYPE_CHANGE_SCHEDULE = 0; // Đổi lịch / đổi phòng
    public const TYPE_ADD_NEW         = 1; // Bổ sung buổi mới

    // Status constants
    public const STATUS_PENDING   = 0;
    public const STATUS_APPROVED = 1;
    public const STATUS_REJECTED  = 2;

    /**
     * The teacher/admin who submitted this request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The course session this request refers to.
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(CourseSession::class, 'session_id');
    }

    /**
     * The original room before the change.
     */
    public function oldRoom(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'old_room_id');
    }

    /**
     * The requested new room.
     */
    public function newRoom(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'new_room_id');
    }

    /**
     * The admin who reviewed this request.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}