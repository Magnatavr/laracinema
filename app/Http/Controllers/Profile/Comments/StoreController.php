<?php

namespace App\Http\Controllers\Profile\Comments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
public function __invoke(StoreRequest $request){
    
    Comment::create([
        'movie_id' => $request->movie_id,
        'user_id'  => Auth::id(),
        'rating'   => $request->rating,
        'comment'  => $request->comment,
    ]);

    return back()->with('success', 'Отзыв успешно добавлен!');
}
}
