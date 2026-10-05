<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'is_suspended', 'is_root_admin'])]
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
}
