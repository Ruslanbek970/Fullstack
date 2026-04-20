<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'avatar',
        'bio',
        'password',
        'banned_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
            'banned_at' => 'datetime',
        ];
    }

    public function memes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Meme::class);
    }

    public function isBanned(): bool
    {
        return $this->banned_at !== null;
    }

    public function avatarUrl(): string
    {
        $v = (string) ($this->updated_at?->getTimestamp() ?? $this->id);

        if ($this->avatar) {
            $relative = ltrim(str_replace('\\', '/', (string) $this->avatar), '/');
            $base = '/storage/'.$relative;

            return $base.'?v='.$v;
        }

        $name = urlencode((string) $this->name);

        return 'https://ui-avatars.com/api/?name='.$name.'&background=1a1a1a&color=ffc107&size=128';
    }
}
