@extends('layouts.app')

@section('title', ' | Search for: ' . e($initialPhrase))

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
        <div class="col-12 col-lg-8 order-2 order-lg-1">

            <h2>Articles</h2>

            <div class="articles">
                @if(count($articles))
                    @foreach($articles as $article)
                        @include('includes.components.article', ['article' => $article, 'fullWidth' => !(Auth::user() && Auth::user()->is('admin')), 'raw' => true])
                    @endforeach
                @else
                <h4 class="no-results">
                    No articles found.
                </h4>
                @endif
            </div>
        </div>

        <div class="col-12 col-lg-4 mb-5 mb-lg-0 order-1 order-lg-2">
            <h2>Advanced Search</h2>

            @include('includes.forms.search')
        </div>

        @if(count($articles))
            
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        //
    })
</script>
@endsection