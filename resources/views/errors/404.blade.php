@extends('layouts.app')

@section('title', ' | 404')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 col-md-10 col-xl-8 offset-md-1 offset-xl-2">
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-muted mb-4">
                Go Back
            </a>

            <div class="w-100 h-100 d-flex flex-column justify-content-center align-content-center" style="min-height: 40vh;">
                <div class="d-flex flex-column align-items-center">
                    <h2 class="align-self-center display-1">404</h2>
                    <p>It seems that the page doesn't exist</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection