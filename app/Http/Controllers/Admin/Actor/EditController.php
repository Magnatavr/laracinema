<?php

namespace App\Http\Controllers\Admin\Actor;

use App\Http\Controllers\Controller;
use App\Models\Actor;

class EditController extends Controller
{
    public function __invoke(Actor $actor)
    {
        return view('admin.actors.edit', compact('actor'));
    }
}
