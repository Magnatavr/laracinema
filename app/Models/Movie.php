<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'year', 'duration', 'rating',
        'age_rating', 'poster', 'banner', 'url', 'trailer_url', 'status'
    ];

    public static array $ageLabels = [
        'G'      => 'Для всех возрастов',
        'PG'     => 'Рекомендуется присутствие родителей',
        'PG-13'  => 'С 13 лет',
        'R'      => 'С 17 лет',
        'NC-17'  => 'Только для взрослых (18+)',
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function actors()
    {
        return $this->belongsToMany(Actor::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}

