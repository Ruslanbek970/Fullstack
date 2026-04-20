<?php

namespace App\Models;

use ApiPlatform\Metadata\ApiResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ApiResource]
class Meme extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_PUBLISHED = 'published';

    public const STATUS_REJECTED = 'rejected';

    public const MEDIA_IMAGE = 'image';

    public const MEDIA_VIDEO = 'video';

    protected $fillable = [
        'user_id',
        'title',
        'category',
        'description',
        'image',
        'media_type',
        'status',
        'rejection_reason',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'meme_likes')->withTimestamps();
    }

    public function dislikedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'meme_dislikes')->withTimestamps();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function likesCount(): int
    {
        return $this->likedBy()->count();
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function isVideo(): bool
    {
        return ($this->media_type ?? self::MEDIA_IMAGE) === self::MEDIA_VIDEO;
    }

    /**
     * Колонка image: внешний URL или путь относительно public disk (memes/...).
     */
    public function mediaUrl(): ?string
    {
        if ($this->image === null || $this->image === '') {
            return null;
        }
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        $relative = ltrim($this->image, '/');
        $url = '/storage/'.ltrim(str_replace('\\', '/', $relative), '/');

        return $url;
    }

    public function canBeViewedBy(?User $user): bool
    {
        if ($this->isPublished()) {
            return true;
        }
        if (! $user) {
            return false;
        }
        if ($this->user_id === $user->id) {
            return true;
        }

        return $user->can('meme.publish') || $user->can('meme.reject');
    }
}
