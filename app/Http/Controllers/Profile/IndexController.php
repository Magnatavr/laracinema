<?php

namespace App\Http\Controllers\Profile;

use Illuminate\Support\Facades\Auth;

class IndexController
{
    public function __invoke()
    {
        $user = Auth::user();
        $user->load(['comments.movie', 'likedMovies']);
        return view('profile.index', compact('user'));
    }

}
