<div class="article-mini my-4 p-0 d-flex flex-column @if($fullWidth) flex-sm-row col-sm-12 @else flex-sm-row flex-lg-row @endif align-items-stretch">
    <div class="col-lg-5 w-100 p-0 m-0">
        <a href="{{ route('articles.show', $article->slug) }}" class="article-mini-photo-container">
            <div class="article-mini-photo" style="background-image: url('{{ $article->coverPath() }}');"></div>
        </a>
    </div>
    <div class="col-lg-7 p-3 m-0">
        <h4 class="article-header">
            @if(isset($raw) && $raw)
            {!! $article->title !!}
            @else
            {{ $article->title }}
            @endif 
        </h4>

        <p class="font-italic text-muted">
            {{ $article->created_at->format('j F, Y') }} by {{ $article->user->name }} {{ $article->user->surname }}
        </p>

        <div class="article-body mb-2">
            {!! \Illuminate\Support\Str::limit($article->body, 240, $end='...') !!}
        </div>

        <a href="{{ route('articles.show', $article->slug) }}">
            Read More
        </a>
    </div>
</div>