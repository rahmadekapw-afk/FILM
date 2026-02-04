<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'ratings';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = null;

    protected $fillable = [
        'userId',
        'movieId',
        'rating',
        'timestamp'
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movieId', 'movieId');
    }
}
