@extends('layouts.app')

@section('title', ' | Category: ' . e($category->name))

@section('links')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/components/article-mini.css') }}">
@endsection

@section('content')
<div class="container-fluid container-md">
    <div class="row">
        <div class="col-12 @if(Auth::user() && Auth::user()->is('admin')) col-lg-8 @else col-lg-10 offset-lg-1 @endif">
            <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-muted mb-4">
                Go Back
            </a>

            <h2>Articles in category: {{ $category->name }}</h2>

            <div class="articles container-fluid p-0 mt-3">
                @if(count($articles))
                    @foreach($articles as $article)
                        @include('includes.components.article', ['article' => $article, 'fullWidth' => !(Auth::user() && Auth::user()->is('admin'))])
                    @endforeach
                @else
                <h4 class="no-results">
                    No articles found in this category.
                </h4>
                @endif
            </div>

            @if(count($articles))
                {!! $articles->render() !!}
            @endif
        </div>

        @if(Auth::user() && Auth::user()->is('admin'))
        <div class="col-12 col-lg-4 mt-4 mt-lg-0">
            <h2>Category</h2>

            @include('includes.forms.category', ['category' => $category])
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')

@endsection