@extends('layouts.app')

@section('title', ' | ' . e($user->login))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 col-sm-10 offset-sm-1">
            <div class="d-flex justify-content-end mb-4">
                <div class="d-flex justify-content-between">
                    @if(Auth::id() && Auth::id() == $user->id)
                    <a class="btn btn-sm btn-outline-primary mr-3" href="{{ route('profiles.edit', $user->id) }}">
                        Edit Profile
                    </a>

                    {!! Form::open([
                        'route' => ['profiles.delete', $user->id],
                        'method' => 'POST',
                        'class' => 'form-inline d-inline-block form-delete-post pull-right',
                    ]) !!}

                        {!! Form::button('Delete Account', [
                            'type' => 'submit',
                            'class' => 'btn btn-sm btn-outline-danger',
                        ]) !!}

                    {!! Form::close() !!}
                    @endif
                </div>
            </div>

            @if(empty($user->email_verified_at) && Auth::id() && Auth::id() == $user->id)
                <div class="alert alert-danger">
                    <span class="d-block">
                        Please check your mailbox for an activation email.
                    </span>
                    <span class="d-block">
                        If you haven't received verification email or it doesn't work,
                        {{ Form::open([
                            'route' => ['verification.resend'],
                            'method' => 'POST',
                            'class' => 'd-inline',
                        ]) }}

                            {!! Form::button('click here', [
                                'type' => 'submit',
                                'class' => 'font-weight-bold',
                                'style' => 'border: none; background-color: transparent; font-family: inherit; padding: 0; margin: 0; display: inline;'
                            ]) !!}

                        {!! Form::close() !!}
                        to resend. 
                    </span>
                </div>
            @endif

            <h2 class="mb-4">
                {{ $user->name . ' ' . $user->surname }}
            </h2>

            <div class="row">
                <div class="col-sm-4">
                    <img src="{{ $user->avatar() }}" style="width: 100%;">
                </div>
            
                <div class="col-sm-8 pl-sm-3">
                    <div class="my-3">
                        <div class="font-weight-bold">
                            {{ __('Login') }} 
                        </div>
                        <div>
                            {{ $user->login }}
                        </div>
                    </div>
                    <div class="my-3">
                        <div class="font-weight-bold">
                            {{ __('Name') }} 
                        </div>
                        <div>
                            {{ $user->name }}
                        </div>
                    </div>
                    <div class="my-3">
                        <div class="font-weight-bold">
                            {{ __('Surname') }} 
                        </div>
                        <div>
                            {{ $user->surname }}
                        </div>
                    </div>
                    <div class="my-3">
                        <div class="font-weight-bold">
                            {{ __('Email') }} 
                        </div>
                        <div>
                            {{ $user->email }}
                        </div>
                    </div>
                    <div class="my-3">
                        <div class="font-weight-bold">
                            {{ __('Info') }} 
                        </div>
                        <div>
                            {{ $user->info }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

@endsection