<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MovieController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title'       => 'required|string|max:255',
                'genres'      => 'required|string',
                'description' => 'nullable|string|max:1000',
                'duration'    => 'nullable|string|max:20',
                'poster'      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
                'video'       => 'nullable|mimes:mp4,webm,ogg,avi,mov|max:512000', // max 500MB
            ]);

            $movie = new Movie();
            $movie->title       = $validated['title'];
            $movie->genres      = $validated['genres'];
            $movie->description = $request->input('description');
            $movie->duration    = $request->input('duration');

            if ($request->hasFile('poster')) {
                $path = $request->file('poster')->store('posters', 'public');
                $movie->poster_filename = $path;
            }

            if ($request->hasFile('video')) {
                $path = $request->file('video')->store('videos', 'public');
                $movie->video_filename = $path;
            }

            $movie->save();

            return redirect('/admin')->with('success', 'Movie "' . $movie->title . '" added successfully!');
        } catch (\Exception $e) {
            Log::error("Movie Store Error: " . $e->getMessage());
            return redirect('/admin')->with('error', 'Failed to add movie: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $movie = Movie::findOrFail($id);

            $validated = $request->validate([
                'title'       => 'required|string|max:255',
                'genres'      => 'required|string',
                'description' => 'nullable|string|max:1000',
                'duration'    => 'nullable|string|max:20',
                'poster'      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
                'video'       => 'nullable|mimes:mp4,webm,ogg,avi,mov|max:512000',
            ]);

            $movie->title       = $validated['title'];
            $movie->genres      = $validated['genres'];
            $movie->description = $request->input('description');
            $movie->duration    = $request->input('duration');

            if ($request->hasFile('poster')) {
                if ($movie->poster_filename) Storage::disk('public')->delete($movie->poster_filename);
                $movie->poster_filename = $request->file('poster')->store('posters', 'public');
            }

            if ($request->hasFile('video')) {
                if ($movie->video_filename) Storage::disk('public')->delete($movie->video_filename);
                $movie->video_filename = $request->file('video')->store('videos', 'public');
            }

            $movie->save();

            return redirect('/admin')->with('success', 'Movie "' . $movie->title . '" updated successfully!');
        } catch (\Exception $e) {
            Log::error("Movie Update Error: " . $e->getMessage());
            return redirect('/admin')->with('error', 'Failed to update: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $movie = Movie::findOrFail($id);
            if ($movie->poster_filename) Storage::disk('public')->delete($movie->poster_filename);
            if ($movie->video_filename)  Storage::disk('public')->delete($movie->video_filename);
            $movie->delete();
            return redirect('/admin')->with('success', 'Movie deleted successfully!');
        } catch (\Exception $e) {
            return redirect('/admin')->with('error', 'Failed to delete: ' . $e->getMessage());
        }
    }

    public function importExcel(Request $request)
    {
        try {
            $request->validate(['file' => 'required|mimes:xlsx,xls,csv|max:2048']);
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\MovieImport, $request->file('file'));
            return redirect('/admin')->with('success', 'Movies imported successfully!');
        } catch (\Exception $e) {
            return redirect('/admin')->with('error', 'Failed to import: ' . $e->getMessage());
        }
    }
}
