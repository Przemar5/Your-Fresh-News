@extends('layouts.app')

@section('title', ' | Edit Category: ' . e($category->name))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 col-sm-10 col-md-8 offset-sm-1 offset-md-2">
            <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-muted mb-4">
                Go Back
            </a>

            <h2>Edit Category: {{ $category->name }}</h2>

            @include('includes.forms.category', ['category' => $category])
        </div>
    </div>
</div>
@endsection