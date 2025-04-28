<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::where('is_accepted', true)
                        ->with(['user', 'category', 'tags'])
                        ->latest()
                        ->paginate(8);
                        
        return view('article.index', compact('articles'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('article.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255|unique:articles',
            'subtitle' => 'required|max:255',
            'body' => 'required',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|string',
        ]);

        $article = new Article();
        $article->title = $validated['title'];
        $article->subtitle = $validated['subtitle'];
        $article->body = $validated['body'];
        $article->category_id = $validated['category_id'];
        $article->user_id = Auth::id();
        $article->slug = Str::slug($validated['title']);
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $article->slug . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/articles', $imageName);
            $article->image = $imageName;
        }
        
        $article->save();
        
        // Gestione dei tag se presenti
        if ($request->has('tags') && !empty($request->tags)) {
            $tags = explode(',', $request->tags);
            $tagIds = [];
            
            foreach ($tags as $tagName) {
                $tag = Tag::updateOrCreate(
                    ['name' => Str::lower(trim($tagName))],
                    ['name' => Str::lower(trim($tagName))]
                );
                $tagIds[] = $tag->id;
            }
            
            $article->tags()->attach($tagIds);
        }
        
        return redirect()->route('homepage')->with('message', 'Articolo creato con successo! Attendi che un revisore lo approvi.');
    }

    public function show(Article $article)
    {
        // Permetti la visualizzazione agli utenti revisori anche se non approvato
        if ($article->is_accepted === false && !Auth::check()) {
            abort(404);
        }
        
        if ($article->is_accepted === null && !(Auth::check() && (Auth::user()->is_revisor || Auth::user()->is_admin || Auth::user()->id == $article->user_id))) {
            abort(404);
        }
        
        return view('article.show', compact('article'));
    }

    public function byCategory(Category $category)
    {
        $articles = Article::where('category_id', $category->id)
                    ->where('is_accepted', true)
                    ->latest()
                    ->paginate(8);
                    
        return view('article.by-category', compact('category', 'articles'));
    }

    public function byUser(User $user)
    {
        $articles = Article::where('user_id', $user->id)
                    ->where('is_accepted', true)
                    ->latest()
                    ->paginate(8);
                    
        return view('article.by-user', compact('user', 'articles'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $articles = Article::search($query)
                    ->where('is_accepted', true)
                    ->latest()
                    ->paginate(8);
                    
        return view('article.search-index', compact('articles', 'query'));
    }

    public function edit(Article $article)
    {
        // Verifica che l'utente sia il proprietario dell'articolo
        if (Auth::id() !== $article->user_id) {
            return redirect()->back()->with('message', 'Non sei autorizzato a modificare questo articolo');
        }
        
        $categories = Category::all();
        return view('article.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        // Verifica che l'utente sia il proprietario dell'articolo
        if (Auth::id() !== $article->user_id) {
            return redirect()->back()->with('message', 'Non sei autorizzato a modificare questo articolo');
        }
        
        $validated = $request->validate([
            'title' => 'required|max:255|unique:articles,title,'.$article->id,
            'subtitle' => 'required|max:255',
            'body' => 'required',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|string',
        ]);
        
        $article->title = $validated['title'];
        $article->subtitle = $validated['subtitle'];
        $article->body = $validated['body'];
        $article->category_id = $validated['category_id'];
        $article->slug = Str::slug($validated['title']);
        
        // Se l'articolo viene modificato, torna in revisione
        $article->is_accepted = null;
        
        if ($request->hasFile('image')) {
            // Cancella la vecchia immagine
            if ($article->image) {
                Storage::delete('public/articles/' . $article->image);
            }
            
            // Carica la nuova immagine
            $image = $request->file('image');
            $imageName = $article->slug . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/articles', $imageName);
            $article->image = $imageName;
        }
        
        $article->save();
        
        // Aggiornamento dei tag
        if ($request->has('tags')) {
            $tags = explode(',', $request->tags);
            $tagIds = [];
            
            foreach ($tags as $tagName) {
                if (!empty(trim($tagName))) {
                    $tag = Tag::updateOrCreate(
                        ['name' => Str::lower(trim($tagName))],
                        ['name' => Str::lower(trim($tagName))]
                    );
                    $tagIds[] = $tag->id;
                }
            }
            
            $article->tags()->sync($tagIds);
        } else {
            $article->tags()->detach();
        }
        
        return redirect()->route('writer.dashboard')->with('message', 'Articolo aggiornato con successo! Attendi che un revisore lo approvi.');
    }

    public function destroy(Article $article)
    {
        // Verifica che l'utente sia il proprietario dell'articolo
        if (Auth::id() !== $article->user_id) {
            return redirect()->back()->with('message', 'Non sei autorizzato a eliminare questo articolo');
        }
        
        // Rimuovi le relazioni con i tag
        $article->tags()->detach();
        
        // Elimina l'immagine
        if ($article->image) {
            Storage::delete('public/articles/' . $article->image);
        }
        
        $article->delete();
        
        return redirect()->route('writer.dashboard')->with('message', 'Articolo eliminato con successo!');
    }
}