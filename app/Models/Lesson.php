<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'title',
        'content_type',
        'content_body',
        'order',
        'open_at',
        'close_at',
    ];

    protected $casts = [
        'order' => 'integer',
        'open_at' => 'datetime',
        'close_at' => 'datetime',
    ];

    public function classModel(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }
}
