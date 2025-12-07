<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskApplication extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'task_id',
        'user_id',
        'status',
        'priority',
    ];

    /**
     * Get the task that this application belongs to.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the user (student) that made this application.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
