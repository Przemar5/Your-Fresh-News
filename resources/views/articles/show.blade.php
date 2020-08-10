@extends('layouts.app')

@section('title', ' | ' . e($article->title))

@section('links')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/articles/show.css') }}"/>
@endsection

@section('content')
<div class="container-fluid container-md">
    <div class="row">
        <div class="col-12 col-md-10 col-xl-8 offset-md-1 offset-xl-2">
            <div class="mb-5">
                <div class="d-flex justify-content-between mb-4">
                    <a href="{{ route('articles.index') }}" class="btn btn-sm btn-outline-muted mr-3">
                        Go Back
                    </a>

                    @if(Auth::user() && ((Auth::user()->is('writer') && Auth::id() === $article->user->first()->id) || Auth::user()->is('admin')))
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('articles.edit', $article->slug) }}" class="btn btn-sm btn-outline-primary mr-3">
                                Edit Article
                            </a>

                            {!! Form::open([
                                'route' => ['articles.destroy', $article->id],
                                'method' => 'DELETE',
                                'class' => 'form-inline d-inline-block form-article-delete pull-right'
                            ]) !!}

                                {!! Form::button('Delete Article', [
                                    'type' => 'submit',
                                    'class' => 'btn btn-sm btn-outline-danger'
                                ]) !!}

                            {!! Form::close() !!}
                        </div>
                    @endif
                </div>

                <h2 class="article-title">{{ $article->title }}</h2>

                <p class="font-italic text-muted">
                    {{ $article->created_at->format('j F, Y') }} by {{ $article->user->first()->name }} {{ $article->user->first()->surname }}
                </p>

                @if(count($article->tags))
                <div class="mb-3">
                    @foreach($article->tags as $tag)
                    <a href="#" class="badge-secondary mr-2">
                        #{{ $tag->name }}
                    </a>
                    @endforeach
                </div>
                @endif

                <img src="{{ $article->coverPath() }}" alt="{{ $article->cover()->description }}" class="w-100 mb-3">

                <div class="article-body mb-2">
                    {!! $article->body !!}
                </div>

                <div class="w-100 text-right resource-actions">

                    {{ Form::open([
                        'route' => ['ratings.rate.article', $article->id],
                        'method' => 'POST',
                        'class' => 'd-inline-block mr-2 mr-lg-3 form-article-like ' . 
                            (($article->likedBy(Auth::id())) ? 'text-primary' : 'text-muted'),
                        'title' => 'Like'
                    ]) }}

                        {{ Form::hidden('rating_type', 'like') }}

                        <div>
                            <label class="mb-0">
                                <i class="fas fa-thumbs-up" style="cursor: pointer;"></i>
                                <button type="submit" class="d-none"></button>
                            </label>
                            <span class="article-likes-count">
                                {{ $article->likes()->count() }}
                            </span>
                        </div>

                    {{ Form::close() }}


                    {{ Form::open([
                        'route' => ['ratings.rate.article', $article->id],
                        'method' => 'POST',
                        'class' => 'd-inline-block mr-2 mr-lg-3 form-article-dislike ' . 
                            (($article->dislikedBy(Auth::id())) ? 'text-primary' : 'text-muted'),
                        'title' => 'Dislike'
                    ]) }}

                        {{ Form::hidden('rating_type', 'dislike') }}

                        <div>
                            <label class="mb-0">
                                <i class="fas fa-thumbs-down" style="cursor: pointer;"></i>
                                <button type="submit" class="d-none"></button>
                            </label>
                            <span class="article-dislikes-count">
                                {{ $article->dislikes()->count() }}
                            </span>
                        </div>

                    {{ Form::close() }}

                </div>
            </div>

            <div class="mt-4 mb-5 p-3 author-tile">
                <div class="img-responsive-container pb-3">
                    <img class="d-block float-left img-responsive" src="{{ $article->user->avatar() }}"/>
                </div>

                <h3>
                    About Author
                </h3>

                <h3>
                    <a href="{{ route('profiles.show', $article->user->id) }}">
                        {{ $article->user->name . ' ' . $article->user->surname }}
                    </a>
                </h3>

                {!! \Illuminate\Support\Str::limit($article->user->info, 360, $end='...') !!}
            </div>

            <hr>

            @if(Auth::user())
                <div class="my-5" id="addCommentSection">
                    <h2>Add Comment</h2>

                    @include('includes.forms.comment', ['articleId' => $article->id])
                </div>
            @else
                <div class="d-flex flex-column justify-content-center align-items-center my-5 p-4 p-sm-5 text-center" style="background-color: #f7f7f9;">
                    <h4 class="mb-3 mb-sm-4">
                        Login to write comments!
                    </h4>

                    <div class="d-flex flex-row justify-content-center align-items-center login-to-comment-actions">
                        <a class="btn btn-primary mr-3" href="{{ route('login') }}">
                            Login
                        </a>
                        <span>
                            or
                        </span>
                        <a class="btn btn-secondary ml-3" href="{{ route('register') }}">
                            Register
                        </a>
                    </div>
                </div>
            @endif

            <hr class="my-5">

            <div class="comments">
                @if(count($article->baseComments()->get()))
                    @foreach($article->baseComments()->get()->sortByDesc('id') as $comment)
                        @include('includes.components.comment', ['comment' => $comment, 'depth' => 2])
                    @endforeach
                @endif

                <h4 class="no-results @if(count($article->baseComments()->get())) d-none @endif">
                    No comments found. Be the first!
                </h4>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script type="text/javascript" src="{{ URL::asset('js/articles/show.js') }}"></script>

<script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>

<script type="text/javascript">
    CKEDITOR.replace('commentBodyForm');
</script>
@endsection