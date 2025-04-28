<footer class="bg-dark text-white py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>The Aulab Post</h5>
                <p>Il tuo blog di notizie e articoli</p>
            </div>
            <div class="col-md-4">
                <h5>Link utili</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('homepage') }}" class="text-white">Home</a></li>
                    <li><a href="{{ route('article.index') }}" class="text-white">Articoli</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Contatti</h5>
                <p>Email: info@aulabpost.com</p>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col text-center">
                <p class="mb-0">© {{ date('Y') }} The Aulab Post. Tutti i diritti riservati.</p>
            </div>
        </div>
    </div>
</footer>