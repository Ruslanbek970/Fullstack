<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = [
        'meme_id',
        'user_id',
        'body',
        'is_hidden',
    ];

    protected function casts(): array
    {
        return [
            'is_hidden' => 'boolean',
        ];
    }

    public function meme(): BelongsTo
    {
        return $this->belongsTo(Meme::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeVisibleFor($query, ?User $viewer)
    {
        if ($viewer && $viewer->can('comment.moderate')) {
            return $query;
        }

        return $query->where('is_hidden', false);
    }
}
