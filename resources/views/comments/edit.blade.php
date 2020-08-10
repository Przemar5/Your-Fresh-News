@extends('layouts.app')

@section('title', ' | Edit Comment: ' . e($comment->title))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 col-md-10 col-xl-8 offset-md-1 offset-xl-2">
            <a href="{{ route('articles.index') }}" class="btn btn-sm btn-outline-muted mb-4">
                Go Back
            </a>

            <h2>Edit Comment: {{ $comment->title }}</h2>

            @include('includes.forms.comment', ['comment' => $comment])
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>

    <script type="text/javascript">
        CKEDITOR.replace('commentBodyForm');
    </script>
@endsection