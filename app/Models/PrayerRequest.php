<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use App\Notifications\NewPrayerRequestNotification;
use App\Notifications\PrayerRequestUpdateNotification;
use Illuminate\Support\Facades\Log;

class PrayerRequest extends Model
{
    use HasFactory, Notifiable;

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

    public function followers()
    {
        return $this->belongsToMany(User::class, 'prayer_request_followers', 'prayer_request_id', 'user_id');
    }

    public function notifyFollowers($update = null)
    {
        Log::info('Notifying followers');
        Log::info($this->followers);
        Log::info($update);
        foreach ($this->followers as $follower) {
            if ($update) {
                $follower->notify(new PrayerRequestUpdateNotification($update));
            } else {
                $follower->notify(new NewPrayerRequestNotification($this));
            }
        }
    }

    protected $appends = ['prayer_count'];
    protected $with = ['updates'];
}
