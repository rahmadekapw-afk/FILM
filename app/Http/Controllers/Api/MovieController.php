<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::with('ratings')->get()->map(function ($movie) {
            return [
                'id' => $movie->movieId,
                'title' => $movie->title,
                'genres' => explode('|', $movie->genres),
                'rating' => $movie->average_rating,
                'year' => $this->extractYear($movie->title),
                'image' => $movie->poster_filename ? asset('storage/' . $movie->poster_filename) : null,
            ];
        });

        return response()->json($movies);
    }

    private function extractYear($title)
    {
        preg_match('/\((19|20)\d{2}\)/', $title, $matches);
        if ($matches) {
            return str_replace(['(', ')'], '', $matches[0]);
        }
        return 'N/A';
    }
}
