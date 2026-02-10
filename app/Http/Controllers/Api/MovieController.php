<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Check if tables exist before querying
            if (!\Illuminate\Support\Facades\Schema::hasTable('movies')) {
                return response()->json([], 200);
            }

            $query = Movie::with('ratings');

            if ($request->has('search')) {
                $searchTerm = $request->query('search');
                $query->where('title', 'like', '%' . $searchTerm . '%');
            }

            $movies = $query->get()->map(function ($movie) {
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
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Movie Fetch Error: " . $e->getMessage());
            return response()->json([], 200); // Return empty array instead of crashing
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'genres' => 'required|string',
                'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            $movie = new Movie();
            $movie->title = $validated['title'];
            $movie->genres = $validated['genres'];

            if ($request->hasFile('poster')) {
                $path = $request->file('poster')->store('posters', 'public');
                $movie->poster_filename = $path;
            }

            $movie->save();

            return response()->json([
                'message' => 'Movie added successfully!',
                'movie' => [
                    'id' => $movie->movieId,
                    'title' => $movie->title,
                    'genres' => explode('|', $movie->genres),
                    'image' => $movie->poster_filename ? asset('storage/' . $movie->poster_filename) : null,
                ]
            ], 201);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Movie Store Error: " . $e->getMessage());
            return response()->json([
                'error' => 'Failed to add movie.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $movie = Movie::findOrFail($id);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'genres' => 'required|string',
                'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            $movie->title = $validated['title'];
            $movie->genres = $validated['genres'];

            if ($request->hasFile('poster')) {
                // Delete old poster if exists
                if ($movie->poster_filename) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($movie->poster_filename);
                }
                $path = $request->file('poster')->store('posters', 'public');
                $movie->poster_filename = $path;
            }

            $movie->save();

            return response()->json([
                'message' => 'Movie updated successfully!',
                'movie' => [
                    'id' => $movie->movieId,
                    'title' => $movie->title,
                    'genres' => explode('|', $movie->genres),
                    'image' => $movie->poster_filename ? asset('storage/' . $movie->poster_filename) : null,
                ]
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Movie Update Error: " . $e->getMessage());
            return response()->json([
                'error' => 'Failed to update movie.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $movie = Movie::findOrFail($id);

            // Delete poster if exists
            if ($movie->poster_filename) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($movie->poster_filename);
            }

            $movie->delete();

            return response()->json(['message' => 'Movie deleted successfully!']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Movie Delete Error: " . $e->getMessage());
            return response()->json([
                'error' => 'Failed to delete movie.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv|max:2048',
            ]);

            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\MovieImport, $request->file('file'));

            return response()->json(['message' => 'Movies imported successfully!']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Excel Import Error: " . $e->getMessage());
            return response()->json([
                'error' => 'Failed to import movies.',
                'message' => $e->getMessage()
            ], 500);
        }
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
