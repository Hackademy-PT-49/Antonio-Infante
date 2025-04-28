<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $fillable = [
        'title', 
        'subtitle', 
        'body', 
        'image', 
        'user_id', 
        'category_id', 
        'is_accepted',
        'slug'
    ];

    // Relazione con l'utente che ha scritto l'articolo
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relazione con la categoria dell'articolo
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relazione molti a molti con i tags
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    // Genera lo slug automaticamente prima di salvare
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            $article->slug = Str::slug($article->title);
        });

        static::updating(function ($article) {
            $article->slug = Str::slug($article->title);
        });
    }

    // Calcola il tempo di lettura
    public function getReadTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->body));
        return max(1, round($wordCount / 200));
    }

    // Metodo per la ricerca full-text (per Laravel Scout)
    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'body' => $this->body,
            'category' => $this->category ? $this->category->name : null,
        ];
    }
}

