<x-layout>
    <div class="container my-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    @if($article->image)
                        <img src="{{ asset('storage/articles/' . $article->image) }}" class="card-img-top" alt="{{ $article->title }}">
                    @endif
                    <div class="card-body">
                        <h1 class="card-title">{{ $article->title }}</h1>
                        <h4 class="card-subtitle mb-3 text-muted">{{ $article->subtitle }}</h4>
                        
                        <div class="d-flex justify-content-between mb-4">
                            <div>
                                <small class="text-muted">
                                    @if($article->category)
                                        Categoria: <a href="#">{{ $article->category->name }}</a>
                                    @endif
                                </small>
                            </div>
                            <div>
                                <small class="text-muted">
                                    Scritto da: <a href="#">{{ $article->user->name }}</a>
                                </small>
                            </div>
                            <div>
                                <small class="text-muted">
                                    {{ $article->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                        
                        <div class="card-text">
                            {!! nl2br(e($article->body)) !!}
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('article.index') }}" class="btn btn-outline-primary">Torna agli articoli</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>