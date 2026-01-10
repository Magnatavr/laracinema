<?php

namespace App\Http\Controllers\Profile\Likes;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Support\Facades\Auth;

class ToggleLike extends Controller
{

    public function __invoke(Movie $movie)
    {
        $user = Auth::user();

        if ($user->hasLiked($movie->id)) {
            $user->likedMovies()->detach($movie->id);
            $message = 'Лайк удален';
            $isLiked = false;
            $action = 'unlike';
        } else {
            $user->likedMovies()->attach($movie->id);
            $message = 'Лайк добавлен';
            $isLiked = true;
            $action = 'like';
        }

        $likesCount = $movie->likes()->count();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => $message, 'is_liked' => $isLiked, 'action' => $action, 'likes_count' => $likesCount,]);
        }

        return back()->with('success', $message);

    }
}
