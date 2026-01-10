<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Genre;

class GenreController extends Controller
{
    public function __invoke(Genre $genre)
    {
        $movies = $genre->movies()->with('genres')->latest()->paginate(12);

        // Популярные жанры для сайдбара
        $popularGenres = Genre::withCount('movies')->orderBy('movies_count', 'desc')->take(10)->get();

        return view('genres.show', ['genre' => $genre, 'movies' => $movies, 'popularGenres' => $popularGenres, 'currentGenre' => $genre // передаем текущий жанр
        ]);
    }
}
