@if(!empty($tag))
    {!! Form::open([
            'route' => ['tags.update', $tag->id],
            'method' => 'PATCH'
    ]) !!}
@else
    {!! Form::open([
        'route' => 'tags.store',
        'method' => 'POST',
        'class' => 'form-create'
    ]) !!}
@endif

    {{ Form::label('name', 'Name') }}
    {{ Form::text('name', $tag->name ?? '', [
        'class' => 'form-control mb-3',
        'required' => '',
        'maxlength' => '255',
        // 'pattern' => '[0-9a-zA-Z_\-\+\&\% ]+',
        'data-validator' => 'true',
        'data-validator-required' => 'true',
        'data-validator-maxlength' => '255',
        'data-validator-pattern' => '[\w\-\+]+',
        'data-validator-required-message' => 'Tag name is required.',
        'data-validator-maxlength-message' => 'Tag name must be equal or shorter than 255 characters long.',
        'data-validator-pattern-message' => 'Tag name may contain only letters, numbers, spaces, underscores, dashes and pluses.',
    ]) }}

    @include('includes.components.error', ['field' => 'name'])

    @if(!empty($tag))
        {!! Form::submit('Edit', [
            'class' => 'btn btn-primary btn-block'
        ]) !!}
    @else
        {!! Form::submit('Create', [
            'class' => 'btn btn-primary btn-block'
        ]) !!}
    @endif

{!! Form::close() !!}