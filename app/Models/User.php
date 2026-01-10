<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'phone',
        'birth_date',
        'about',
        'city',
        'email_notifications',
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
            'birth_date' => 'date',
            'email_notifications' => 'boolean'
        ];
    }

    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class);
    }

    public function likedMovies()
    {
        return $this->belongsToMany(Movie::class, 'likes')
            ->withTimestamps();
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        // Генерация аватарки по умолчанию
        $initials = strtoupper(substr($this->name, 0, 2));
        $colors = ['FF6B6B', '4ECDC4', '45B7D1', '96CEB4', 'FFEAA7'];
        $color = $colors[array_rand($colors)];

        return "https://ui-avatars.com/api/?name={$initials}&background={$color}&color=fff&size=200";
    }

    public function getAgeAttribute()
    {
        if (!$this->birth_date) {
            return null;
        }

        return $this->birth_date->age;
    }

    // Accessor для формата телефона
    public function getFormattedPhoneAttribute()
    {
        if (!$this->phone) {
            return 'Не указан';
        }

        return preg_replace('/(\d{1})(\d{3})(\d{3})(\d{2})(\d{2})/', '$1 ($2) $3-$4-$5', $this->phone);
    }

    // Проверяет лайкнул ли пользователь фильм
    public function hasLiked($movieId)
    {
        return $this->likedMovies()->where('movie_id', $movieId)->exists();
    }
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
