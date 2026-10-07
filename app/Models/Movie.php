<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'genre',
        'status',
        'access_tier',
        'poster_url',
        'banner_url',
        'release_year',
        'rating',
        'views_count',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'rating' => 'float',
        'views_count' => 'integer',
    ];

    /**
     * Boot model events.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($movie) {
            if (empty($movie->slug)) {
                $movie->slug = Str::slug($movie->title).'-'.Str::random(5);
            }
        });
    }

    /**
     * Creator owner relation.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Episodes relation.
     */
    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class)->orderBy('episode_number', 'asc');
    }

    /**
     * Users who favorited this movie.
     */
    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites', 'movie_id', 'user_id')->withTimestamps();
    }

    /**
     * Check if movie is favorited by user.
     */
    public function isFavoritedBy(int|User|null $user): bool
    {
        if (! $user) {
            return false;
        }

        $userId = $user instanceof User ? $user->id : (int) $user;

        return $this->favoritedByUsers()->where('user_id', $userId)->exists();
    }

    /**
     * Get total favorites count.
     */
    public function favoritesCount(): int
    {
        return $this->favoritedByUsers()->count();
    }

    /**
     * Get status label.
     */
    public function isOngoing(): bool
    {
        return strtolower($this->status) === 'ongoing';
    }

    /**
     * Status display label.
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->isOngoing() ? 'Ongoing' : 'Completed';
    }
}
