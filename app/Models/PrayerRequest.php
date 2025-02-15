<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PrayerRequest extends Model
{
    protected $fillable = [
        'user_id',
        'request',
        'is_praise',
        'follow_up_email',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function prayerCounts(): HasMany
    {
        return $this->hasMany(PrayerCount::class);
    }

    public function getPrayerCountAttribute()
    {
        return $this->prayerCounts()->count();
    }

    protected $appends = ['prayer_count'];
}
