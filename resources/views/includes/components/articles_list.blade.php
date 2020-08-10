<div class="container-fluid container-md">
    <div class="row">
        <div class="col-12 col-lg-8">
            <a href="{{ $redirect }}" class="btn btn-sm btn-outline-muted mb-4">
                Go Back
            </a>

            <h2>{{ $header }}</h2>

            <div class="articles">
                @if(count($articles))
                    @foreach($articles as $article)
                        @include('includes.components.article', ['article' => $article, 'fullWidth' => !(Auth::user() && Auth::user()->is('admin'))])
                    @endforeach
                @else
                <h4 class="no-results">
                    No articles found.
                </h4>
                @endif
            </div>
            
            {!! $articles->render() !!}
        </div>

        <div class="col-12 col-lg-4 mt-4 mt-lg-0">
            <h2>Advanced Search</h2>

            @include('includes.forms.search')
        </div>
    </div>
</div>