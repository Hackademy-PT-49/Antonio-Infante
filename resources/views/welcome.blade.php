<x-layout>
    <div class="container my-5">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h1>Benvenuto su The Aulab Post</h1>
                <p class="lead">Il tuo portale di informazione e notizie</p>
            </div>
        </div>
        
        <div class="row">
            @forelse($articles as $article)
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100">
                        @if($article->image)
                            <img src="{{ Storage::url('articles/'.$article->image) }}" class="card-img-top" alt="{{ $article->title }}">
                        @else
                            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Placeholder">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $article->title }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">{{ $article->subtitle }}</h6>
                            <p class="card-text">
                                <small class="text-muted">
                                    @if($article->category)
                                        Categoria: <a href="{{ route('article.by-category', $article->category) }}">{{ $article->category->name }}</a>
                                    @endif
                                </small>
                            </p>
                            <p class="card-text">
                                <small class="text-muted">
                                    Scritto da: <a href="{{ route('article.by-user', $article->user) }}">{{ $article->user->name }}</a>
                                </small>
                            </p>
                            <p class="card-text">
                                <small class="text-muted">
                                    {{ $article->created_at->format('d/m/Y') }}
                                </small>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('article.show', $article) }}" class="btn btn-primary">Leggi</a>
                                <span class="text-muted">{{ $article->readTime ?? '1' }} min di lettura</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center" role="alert">
                        Non ci sono ancora articoli da visualizzare.
                        @auth
                            <p class="mt-3">
                                <a href="{{ route('article.create') }}" class="btn btn-primary">Scrivi il primo articolo</a>
                            </p>
                        @else
                            <p class="mt-3">
                                <a href="{{ route('login') }}" class="btn btn-primary">Accedi per scrivere</a>
                            </p>
                        @endauth
                    </div>
                </div>
            @endforelse
        </div>

        @auth
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <a href="{{ route('article.create') }}" class="btn btn-success">
                        <i class="fas fa-plus-circle"></i> Scrivi un nuovo articolo
                    </a>
                </div>
            </div>
        @endauth
    </div>
</x-layout>
