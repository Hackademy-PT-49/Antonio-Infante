<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function homepage()
    {
        // Recupera gli ultimi 4 articoli accettati, con le relazioni user e category
        $articles = Article::where('is_accepted', true)
            ->with(['user', 'category'])
            ->latest()
            ->take(4)
            ->get();

        // Passa gli articoli alla vista welcome
        return view('welcome', compact('articles'));
    }
}
