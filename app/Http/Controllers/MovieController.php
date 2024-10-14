<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieResource;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::with('user')->get();
        return MovieResource::collection($movies);
    }

    public function store(StoreMovieRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['thumbnail'] = $request->file('thumbnail')->store('movies', 'public');
        $validatedData['user_id'] = auth()->id();

        $movie = Movie::create($validatedData);

        return new MovieResource($movie);
    }

    public function show(Movie $movie)
    {
        $movie->load('user');
        return new MovieResource($movie);
    }

    public function update(UpdateMovieRequest $request, Movie $movie)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('thumbnail')) {

            if ($movie->thumbnail) {
                Storage::disk('public')->delete($movie->thumbnail);
            }
            $validatedData['thumbnail'] = $request->file('thumbnail')->store('movies', 'public');
        }

        $movie->update($validatedData);

        return new MovieResource($movie);
    }

    public function destroy(Movie $movie)
    {
        if ($movie->thumbnail) {
            Storage::disk('public')->delete($movie->thumbnail);
        }

        $movie->delete();

        return response()->json(null, 204);
    }
}
