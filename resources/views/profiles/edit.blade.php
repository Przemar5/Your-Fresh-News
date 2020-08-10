@extends('layouts.app')

@section('title', ' | Edit Profile: ' . e($user->login))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 col-sm-10 offset-sm-1">
            <a href="{{ route('profiles.show', $user->id) }}" class="btn btn-sm btn-outline-muted mb-4">
                Go Back
            </a>

            <h2>
                Edit Profile of {{ $user->name . ' ' . $user->surname }}
            </h2>

            {!! Form::open([
                'route' => ['profiles.update', $user->id],
                'method' => 'PATCH',
                'files' => true,
            ]) !!}

                {{ Form::label('login', 'Login') }}
                {{ Form::text('login', $user->login, [
                    'class' => 'form-control mb-3',
                    'required' => '',
                    'minlength' => '2',
                    'maxlength' => '255',
                    'pattern' => '[\w\-\@\#\&\+\/\.]+',
                    'data-validator' => 'true',
                    'data-validator-required' => 'true',
                    'data-validator-minlength' => '2',
                    'data-validator-maxlength' => '255',
                    'data-validator-pattern' => '^[\w\-\@\#\&\+\/\.]+$',
                    'data-validator-required-message' => 'Login is required.',
                    'data-validator-minlength-message' => 'Login must be equal or longer than 2 characters long.',
                    'data-validator-maxlength-message' => 'Login must be equal or shorter than 255 characters long.',
                    'data-validator-pattern-message' => 'Login contains forbidden characters.',
                ]) }}

                @include('includes.components.error', ['field' => 'login'])

                {{ Form::label('password', 'Password') }}
                {{ Form::password('password', [
                    'class' => 'form-control mb-3',
                    'minlength' => '8',
                    'maxlength' => '255',
                ]) }}

                {{ Form::label('password_confirmation', 'Repeat Password') }}
                {{ Form::password('password_confirmation', [
                    'class' => 'form-control mb-3',
                    'minlength' => '8',
                    'maxlength' => '255',
                ]) }}

                @include('includes.components.error', ['field' => 'password'])

                {{ Form::label('email', 'Email') }}
                {{ Form::email('email', $user->email, [
                    'class' => 'form-control mb-3',
                    'required' => '',
                    'maxlength' => '255',
                    'data-validator' => 'true',
                    'data-validator-required' => 'true',
                    'data-validator-maxlength' => '255',
                    'data-validator-required-message' => 'Email address is required.',
                    'data-validator-maxlength-message' => 'Email address must be equal or shorter than 255 characters long.',
                ]) }}

                @include('includes.components.error', ['field' => 'email'])

                {{ Form::label('avatar', 'Avatar') }}
                {{ Form::file('avatar', [
                    'class' => 'form-control mb-3',
                ]) }}

                @include('includes.components.error', ['field' => 'avatar'])

                <div class="form-check mb-3">
                    <input type="checkbox" name="delete_avatar" class="form-check-input">
                    <label class="form-check-label">
                        Delete current avatar
                    </label>
                </div>

                {{ Form::label('info', 'About') }}
                {{ Form::textarea('info', $user->info, [
                    'class' => 'form-control mb-3',
                    'rows' => '5',
                    'style' => 'resize: none;',
                ]) }}

                @include('includes.components.error', ['field' => 'info'])

                {!! Form::submit('Edit', [
                    'class' => 'btn btn-primary btn-block'
                ]) !!}

            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection