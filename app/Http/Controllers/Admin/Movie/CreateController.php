<?php

namespace App\Http\Controllers\Admin\Movie;

use App\Http\Controllers\Controller;
use App\Models\Actor;
use App\Models\Genre;

class CreateController extends Controller
{
    public function __invoke()
    {
        $actors = Actor::all();
        $genres = Genre::all();

        return view('admin.movies.create', compact('actors', 'genres'));

    }
}
