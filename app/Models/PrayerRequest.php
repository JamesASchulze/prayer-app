<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PrayerRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'user_id',
        'organization_id',
        'request',
        'is_praise',
        'updates',
    ];

    protected $casts = [
        'is_praise' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function prayerCounts(): HasMany
    {
        return $this->hasMany(PrayerCount::class);
    }

    public function getPrayerCountAttribute()
    {
        return $this->prayerCounts()->count();
    }

    public function updates(): HasMany
    {
        return $this->hasMany(PrayerRequestUpdate::class);
    }

    protected $appends = ['prayer_count'];
    protected $with = ['updates'];
}
