<?php

namespace App\Http\Controllers\Admin\Movie;

use App\Http\Controllers\Controller;
use App\Models\Movie;

class ShowController extends Controller
{
    public function __invoke(Movie $movie)
    {
        $movie->load('genres', 'actors');

        return view('admin.movies.show', compact('movie'));
    }
}
