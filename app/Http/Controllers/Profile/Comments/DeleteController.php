<?php

namespace App\Http\Controllers\Profile\Comments;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class DeleteController extends Controller
{
    public function __invoke(Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Вы не можете удалить этот комментарий');
        }
        $comment->delete();
        return back()->with('success', 'Комментарий удален!');
    }
}
