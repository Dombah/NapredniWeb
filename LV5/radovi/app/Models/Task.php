<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'naziv_rada',
        'naziv_rada_eng',
        'zadatak_rada',
        'tip_studija',
    ];

    /**
     * Get the user (nastavnik) that owns the task.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the applications for this task.
     */
    public function applications()
    {
        return $this->hasMany(TaskApplication::class);
    }

    /**
     * Check if a specific user has applied to this task.
     */
    public function hasApplied($userId)
    {
        return $this->applications()->where('user_id', $userId)->exists();
    }

    /**
     * Get the application for a specific user.
     */
    public function getApplicationFor($userId)
    {
        return $this->applications()->where('user_id', $userId)->first();
    }
}
