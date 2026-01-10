<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'photo', 'bio', 'birthday'];

    protected $casts = [
        'birthday' => 'date',
    ];
    public function getBirthdayFormattedAttribute()
    {
        return $this->birthday
            ? $this->birthday->format('d.m.Y')
            : null;
    }
    public function movies()
    {
        return $this->belongsToMany(Movie::class);
    }
}
