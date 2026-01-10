<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request->get('query');

        if (mb_strlen($query) < 2) {
            return response()->json([]);
        }
        return Movie::where('title', 'LIKE', '%' . $query . '%')->select('id', 'title', 'poster')->limit(10)->get();
    }
}
