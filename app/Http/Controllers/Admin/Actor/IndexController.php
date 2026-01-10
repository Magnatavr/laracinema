<?php

namespace App\Http\Controllers\Admin\Actor;

use App\Http\Controllers\Controller;
use App\Models\Actor;

class IndexController extends Controller
{
    public function __invoke()
    {
        $actors = Actor::orderBy('id', 'desc')->paginate(20);
        return view('admin.actors.index', compact('actors'));
    }
}
