@extends('layouts.app')

@section('title', ' | Articles')

@section('links')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/components/article-mini.css') }}">

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>
@endsection

@section('content')
<div class="container-fluid container-md">
    <div class="row">
        <div class="col-12 col-lg-8">
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-muted mb-4">
                Go Back
            </a>

            <h2>Articles</h2>

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

            @include('includes.forms.search', ['initialCategories' => $article->categories, 'initialTags' => $article->tags])
        </div>
    </div>
</div>
@endsection

@section('scripts')

@endsection