<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class PageController extends Controller
{
    private function mapMovie(Movie $movie): array
    {
        return [
            'id'          => $movie->movieId,
            'title'       => $movie->title,
            'cleanTitle'  => trim(explode('(', $movie->title)[0]),
            'genres'      => array_filter(explode('|', $movie->genres ?? '')),
            'rating'      => $movie->average_rating,
            'year'        => $this->extractYear($movie->title),
            'image'       => $movie->poster_filename ? asset('storage/' . $movie->poster_filename) : null,
            'video'       => $movie->video_filename  ? asset('storage/' . $movie->video_filename)  : null,
            'description' => $movie->description ?? 'An amazing film waiting to be explored.',
            'duration'    => $movie->duration ?? 'N/A',
        ];
    }

    private function getMovies($search = null)
    {
        if (!Schema::hasTable('movies')) return collect([]);

        try {
            $query = Movie::with('ratings');
            if ($search) $query->where('title', 'like', '%' . $search . '%');
            return $query->get()->map(fn($m) => $this->mapMovie($m));
        } catch (\Exception $e) {
            \Log::error("Movie Fetch Error: " . $e->getMessage());
            return collect([]);
        }
    }

    public function landing(Request $request)
    {
        $movies = $this->getMovies($request->query('q'));
        return view('pages.landing', [
            'movies'      => $movies,
            'featured'    => $movies->first(),
            'searchQuery' => $request->query('q', ''),
        ]);
    }

    public function home(Request $request)
    {
        $movies = $this->getMovies($request->query('q'));
        return view('pages.home', [
            'movies'      => $movies,
            'featured'    => $movies->first(),
            'user'        => Auth::user(),
            'searchQuery' => $request->query('q', ''),
        ]);
    }

    public function login()
    {
        if (Auth::check()) return redirect('/home');
        return view('pages.login');
    }

    public function register()
    {
        if (Auth::check()) return redirect('/home');
        return view('pages.register');
    }

    public function admin()
    {
        $movies = $this->getMovies();
        return view('pages.admin', ['movies' => $movies]);
    }

    public function watch($id)
    {
        try {
            $movie = Movie::with('ratings')->findOrFail($id);
            $mapped = $this->mapMovie($movie);

            // Related movies (same genre, exclude current)
            $genres = array_filter(explode('|', $movie->genres ?? ''));
            $related = collect([]);
            if (!empty($genres)) {
                $related = Movie::with('ratings')
                    ->where('movieId', '!=', $id)
                    ->where(function ($q) use ($genres) {
                        foreach ($genres as $genre) {
                            $q->orWhere('genres', 'like', '%' . $genre . '%');
                        }
                    })
                    ->limit(12)
                    ->get()
                    ->map(fn($m) => $this->mapMovie($m));
            }

            return view('pages.watch', [
                'movie'   => $mapped,
                'related' => $related,
            ]);
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Movie not found.');
        }
    }

    private function extractYear($title): string
    {
        preg_match('/\((19|20)\d{2}\)/', $title, $matches);
        return $matches ? str_replace(['(', ')'], '', $matches[0]) : 'N/A';
    }
}
