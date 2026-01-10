<?php

namespace App\Http\Controllers\Profile\Likes;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();
        $movies = $user->likedMovies()->paginate(12);

        return view('profile.partials.likes', compact('user', 'movies'));
    }
}
