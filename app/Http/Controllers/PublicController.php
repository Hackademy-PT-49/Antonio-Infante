<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function homepage()
    {
        // Recupera gli ultimi 4 articoli, indipendentemente dallo stato di approvazione (per test)
        $articles = Article::with(['user', 'category'])
            ->latest()
            ->take(4)
            ->get();

        // Passa gli articoli alla vista welcome
        return view('welcome', compact('articles'));
    }
}
