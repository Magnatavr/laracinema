<?php

namespace App\Http\Controllers\Admin\Movie;

use App\Http\Controllers\Controller;
use App\Models\Actor;
use App\Models\Genre;
use App\Models\Movie;

class EditController extends Controller
{
    public function __invoke(Movie $movie)
    {

        $genres = Genre::all();
        $actors = Actor::all();

        return view('admin.movies.edit', compact('movie', 'genres', 'actors'));
    }
}
