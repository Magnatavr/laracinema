<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Movie;

class ShowController extends Controller
{
    public function __invoke(Movie $movie)
    {
        $movie->load(['genres', 'actors', 'comments.user']);

        return view('movies.show', [
            'movie'          => $movie,
            'reviews'        => $movie->comments()->latest()->get(),
        ]);
    }
}
