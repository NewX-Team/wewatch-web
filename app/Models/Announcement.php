<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'type',
        'target_role',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function getTypeBadgeColorAttribute(): string
    {
        return match ($this->type) {
            'promo' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
            'warning' => 'bg-red-500/10 text-red-400 border-red-500/20',
            'event' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
            default => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'promo' => 'PROMOSI',
            'warning' => 'PERINGATAN',
            'event' => 'EVENT & SPESIAL',
            default => 'INFORMASI',
        };
    }
}
