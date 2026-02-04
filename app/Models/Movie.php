<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $table = 'movies';
    protected $primaryKey = 'movieId';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'poster_filename',
        'genres'
    ];

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'movieId', 'movieId');
    }

    public function getAverageRatingAttribute()
    {
        return round($this->ratings()->avg('rating'), 1) ?: 0;
    }
}
