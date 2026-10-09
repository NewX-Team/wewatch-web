<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'subscription_tier', 'dm_access_tier', 'is_suspended', 'is_root_admin', 'is_verified', 'handle', 'bio', 'avatar_url', 'banner_url', 'tagline'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'is_suspended' => 'boolean',
            'is_root_admin' => 'boolean',
            'is_verified' => 'boolean',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === UserRole::SuperAdmin;
    }

    public function isRootAdmin(): bool
    {
        return (bool) $this->is_root_admin;
    }

    public function isCreator(): bool
    {
        return $this->role === UserRole::Creator;
    }

    public function isUser(): bool
    {
        return $this->role === UserRole::User;
    }

    public function isSuspended(): bool
    {
        return (bool) $this->is_suspended;
    }

    public function isVerified(): bool
    {
        return (bool) $this->is_verified;
    }

    public function getEffectiveSubscriptionTier(): string
    {
        if ($this->isSuperAdmin() || $this->isCreator()) {
            return 'vip';
        }

        return $this->subscription_tier ?: 'free';
    }

    public function canAccessEpisodeTier(string $episodeTier): bool
    {
        if ($this->isSuperAdmin() || $this->isCreator()) {
            return true;
        }

        $userTier = $this->getEffectiveSubscriptionTier();

        if ($userTier === 'vip') {
            return true;
        }

        if ($userTier === 'pro') {
            return in_array($episodeTier, ['free', 'pro']);
        }

        return $episodeTier === 'free';
    }

    public function canDeleteUser(User $targetUser): bool
    {
        if ($targetUser->isRootAdmin()) {
            return false;
        }

        if ($targetUser->isSuperAdmin()) {
            return $this->isRootAdmin();
        }

        return $this->isSuperAdmin();
    }

    public function hasRole(UserRole|string $role): bool
    {
        if ($role instanceof UserRole) {
            return $this->role === $role;
        }

        return $this->role?->value === $role;
    }

    /**
     * User's published movies.
     */
    public function movies(): HasMany
    {
        return $this->hasMany(Movie::class, 'user_id');
    }

    /**
     * Creators that this user is subscribed to.
     */
    public function subscribedCreators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'subscriptions', 'user_id', 'creator_id')->withTimestamps();
    }

    /**
     * Users subscribing to this creator.
     */
    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'subscriptions', 'creator_id', 'user_id')->withTimestamps();
    }

    /**
     * Check if user is subscribed to a target creator.
     */
    public function isSubscribedTo(int|User $creator): bool
    {
        $creatorId = $creator instanceof User ? $creator->id : (int) $creator;

        return $this->subscribedCreators()->where('creator_id', $creatorId)->exists();
    }

    /**
     * Get real-time subscriber count.
     */
    public function subscribersCount(): int
    {
        return $this->subscribers()->count();
    }

    /**
     * Get formatted real-time subscriber count.
     */
    public function subscribersCountFormatted(): string
    {
        $count = $this->subscribersCount();

        if ($count >= 1000000) {
            return number_format($count / 1000000, 1).'M Subscribers';
        }

        if ($count >= 1000) {
            return number_format($count / 1000, 1).'k Subscribers';
        }

        return $count === 1 ? '1 Subscriber' : $count.' Subscribers';
    }

    /**
     * Movies favorited by this user.
     */
    public function favoriteMovies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'favorites', 'user_id', 'movie_id')->withTimestamps();
    }

    /**
     * Check if user has favorited a movie.
     */
    public function isFavorite(int|Movie $movie): bool
    {
        $movieId = $movie instanceof Movie ? $movie->id : (int) $movie;

        return $this->favoriteMovies()->where('movie_id', $movieId)->exists();
    }

    /**
     * Toggle favorite status for a movie.
     */
    public function toggleFavorite(int|Movie $movie): bool
    {
        $movieId = $movie instanceof Movie ? $movie->id : (int) $movie;
        $result = $this->favoriteMovies()->toggle($movieId);

        return count($result['attached']) > 0;
    }

    /**
     * Check if user can send message to admin team.
     * Temporarily open to all users as requested.
     */
    public function canMessageAdmin(): bool
    {
        return true;
    }

    /**
     * Check if creator can receive DM from target sender user.
     * Temporarily open to all users as requested.
     */
    public function canReceiveDmFrom(User $sender): bool
    {
        return true;
    }

    /**
     * Messages sent by this user.
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Messages received by this user.
     */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
}
