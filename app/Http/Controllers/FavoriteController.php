<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    /**
     * Display the user's favorite movies list page.
     */
    public function index(Request $request): View
    {
        // Currently empty collection as favorite movies feature uses template/dummy dataset
        $favorites = collect();

        return view('favorites.index', compact('favorites'));
    }
}
