<x-layout>
    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">Tutti gli articoli</h1>
            </div>
        </div>
        
        <div class="row">
            @forelse($articles as $article)
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100">
                        @if($article->image)
                            <img src="{{ asset('storage/articles/' . $article->image) }}" class="card-img-top" alt="{{ $article->title }}">
                        @else
                            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Placeholder">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $article->title }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">{{ $article->subtitle }}</h6>
                            <p class="card-text">
                                <small class="text-muted">
                                    @if($article->category)
                                        Categoria: <a href="#">{{ $article->category->name }}</a>
                                    @endif
                                </small>
                            </p>
                            <p class="card-text">
                                <small class="text-muted">
                                    Scritto da: <a href="#">{{ $article->user->name }}</a>
                                </small>
                            </p>
                            <p class="card-text">
                                <small class="text-muted">
                                    {{ $article->created_at->format('d/m/Y') }}
                                </small>
                            </p>
                            <a href="{{ route('article.show', $article) }}" class="btn btn-primary">Leggi</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center">Nessun articolo disponibile</p>
                </div>
            @endforelse
        </div>
        
        <div class="row mt-4">
            <div class="col-12 d-flex justify-content-center">
                {{ $articles->links() }}
            </div>
        </div>
    </div>
</x-layout>