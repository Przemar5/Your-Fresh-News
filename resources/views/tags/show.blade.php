@extends('layouts.app')

@section('title', ' | Tag: ' . e($tag->name))

@section('links')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/components/article-mini.css') }}">
@endsection

@section('content')
<div class="container-fluid container-md">
    <div class="row">
        <div class="col-12 @if(Auth::user() && Auth::user()->is('admin')) col-lg-8 @else col-lg-10 offset-lg-1 @endif">
            <a href="{{ route('tags.index') }}" class="btn btn-sm btn-outline-muted mb-4">
                Go Back
            </a>

            <h2>Articles with tag: {{ $tag->name }}</h2>

            <div class="articles mt-3">
                @if(count($articles))
                    @foreach($articles as $article)
                        @include('includes.components.article', ['article' => $article, 'fullWidth' => !(Auth::user() && Auth::user()->is('admin'))])
                    @endforeach
                @else
                <h4 class="no-results">
                    No articles found with this tag.
                </h4>
                @endif
            </div>
            
            @if(count($articles))
                {!! $articles->render() !!}
            @endif
        </div>

        @if(Auth::user() && Auth::user()->is('admin'))
            <div class="col-12 col-lg-4 mt-4 mt-lg-0">
                <h2>Tag</h2>

                {!! Form::open([
                    'route' => ['tags.update', $tag->id],
                    'method' => 'PATCH',
                    'class' => 'form-edit'
                ]) !!}

                    {{ Form::label('name', 'Name') }}
                    {{ Form::text('name', $tag->name, [
                        'class' => 'form-control mb-3',
                        'required' => '',
                        'maxlength' => '255',
                        // 'pattern' => '[0-9a-zA-Z_\-\+]',
                        'data-validator' => 'true',
                        'data-validator-required' => 'true',
                        'data-validator-maxlength' => '255',
                        'data-validator-pattern' => '[\w\-\+]+',
                        'data-validator-required-message' => 'Tag name is required.',
                        'data-validator-maxlength-message' => 'Tag name must be equal or shorter than 255 characters long.',
                        'data-validator-pattern-message' => 'Tag name may contain only letters, numbers, spaces, underscores, dashes and pluses.',
                    ]) }}
                    
                    {!! Form::submit('Update', [
                        'class' => 'btn btn-primary btn-block'
                    ]) !!}

                {!! Form::close() !!}
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        // $('body').on('submit', '.form-edit', function (e) {
        //     e.preventDefault()

        //     $(this).parsley()

        //     let route = $(this).attr('action')

        //     $.ajaxSetup({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         }
        //     })

        //     $.ajax({
        //         url: route,
        //         method: 'PATCH',
        //         data: $(this).serialize(),
        //         success: (data) => console.log(data),
        //         fail: (data) => console.log('failure')
        //     })
        // })
    })
</script>
@endsection