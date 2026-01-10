<?php

namespace App\Http\Controllers\Profile\Comments;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();
        $comments = $user->comments()->with('movie')->latest()->paginate(10);
        return view('profile.partials.comments', compact('user', 'comments'));
    }
}
