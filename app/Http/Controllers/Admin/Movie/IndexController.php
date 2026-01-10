<?php

namespace App\Http\Controllers\Admin\Movie;

use App\Http\Controllers\Controller;
use App\Models\Movie;

class IndexController extends Controller
{
    public function __invoke()
    {
        $movies = Movie::with('genres')->get();

        return view('admin.movies.index', compact('movies'));
    }
}
