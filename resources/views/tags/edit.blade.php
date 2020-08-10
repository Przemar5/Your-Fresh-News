@extends('layouts.app')

@section('title', ' | Edit Tag: ' . e($tag->name))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 col-sm-10 col-md-8 offset-sm-1 offset-md-2">
            <a href="{{ route('tags.index') }}" class="btn btn-sm btn-outline-muted mb-4">
                Go Back
            </a>

            <h2>Edit tag: {{ $tag->name }}</h2>

            @include('includes.forms.tag', ['tag' => $tag])
        </div>
    </div>
</div>
@endsection