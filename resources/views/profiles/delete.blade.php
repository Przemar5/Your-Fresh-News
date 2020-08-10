@extends('layouts.app')

@section('title', ' | Delete Account: ' . e($user->login))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 col-sm-10 offset-sm-1">
            <a href="{{ route('profiles.show', $user->id) }}" class="btn btn-sm btn-outline-muted mb-4">
                Go Back
            </a>

            <h2 class=" text-center mb-3">
                <span class="d-block">
                    Are You sure You want to delete your account?
                </span>
                <span class="d-block">
                    This action is irreversible
                </span>
            </h2>

            {!! Form::open([
                'route' => ['profiles.destroy', $user->id],
                'method' => 'DELETE',
                'class' => 'text-center',
            ]) !!}

                {{ Form::button('Delete Account', [
                    'type' => 'submit',
                    'class' => 'btn btn-danger',
                ]) }}

            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection