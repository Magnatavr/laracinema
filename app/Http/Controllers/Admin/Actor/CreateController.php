<?php

namespace App\Http\Controllers\Admin\Actor;

use App\Http\Controllers\Controller;

class CreateController extends Controller
{
    public function __invoke()
    {
        return view('admin.actors.create');
    }
}
