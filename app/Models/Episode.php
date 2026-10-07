<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Episode extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'episode_number',
        'title',
        'description',
        'duration',
        'access_tier',
        'video_url',
        'thumbnail_url',
    ];

    /**
     * Parent movie relation.
     */
    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    public function isFree(): bool
    {
        return $this->access_tier === 'free';
    }

    public function isPro(): bool
    {
        return $this->access_tier === 'pro';
    }

    public function isVip(): bool
    {
        return $this->access_tier === 'vip';
    }

    /**
     * Check if a given user can access/play this episode based on their subscription tier.
     */
    public function canBeAccessedBy(?User $user): bool
    {
        if (! $user) {
            return $this->isFree();
        }

        if ($user->isSuperAdmin() || $user->isCreator()) {
            return true;
        }

        $tier = $user->subscription_tier ?? 'free';

        if ($tier === 'vip') {
            return true;
        }

        if ($tier === 'pro') {
            return in_array($this->access_tier, ['free', 'pro']);
        }

        return $this->access_tier === 'free';
    }
}
