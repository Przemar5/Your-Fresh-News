@if(!empty($category))
    {!! Form::open([
            'route' => ['categories.update', $category->id],
            'method' => 'PATCH'
    ]) !!}
@else
    {!! Form::open([
        'route' => 'categories.store',
        'method' => 'POST',
        'class' => 'form-create'
    ]) !!}
@endif

    {{ Form::label('name', 'Name') }}
    {{ Form::text('name', $category->name ?? '', [
        'class' => 'form-control mb-3',
        'required' => '',
        'maxlength' => '255',
        'pattern' => '[\w\-\+\#\$\%\^\&\@\!\(\)\|\=\,\.\<\>\/\?\" ]+',
        'data-validator' => 'true',
        'data-validator-required' => 'true',
        'data-validator-maxlength' => '255',
        'data-validator-pattern' => '^[\w\-\+\#\$\%\^\&\@\!\(\)\|\=\,\.\<\>\/\?\" ]+$',
        'data-validator-required-message' => 'Category name is required.',
        'data-validator-maxlength-message' => 'Category name must be equal or shorter than 255 characters long.',
        'data-validator-pattern-message' => 'Category name contains forbidden characters.',
    ]) }}

    @include('includes.components.error', ['field' => 'name'])

    {{ Form::label('slug', 'Slug') }}
    {{ Form::text('slug', $category->slug ?? '', [
        'class' => 'form-control mb-3',
        'required' => '',
        'maxlength' => '255',
        'pattern' => '[0-9a-zA-Z_\-]+',
        'data-validator' => 'true',
        'data-validator-required' => 'true',
        'data-validator-maxlength' => '255',
        'data-validator-pattern' => '[0-9a-zA-Z_\-]+',
        'data-validator-required-message' => 'Category slug is required.',
        'data-validator-maxlength-message' => 'Category slug must be equal or shorter than 255 characters long.',
        'data-validator-pattern-message' => 'Category name may contain only letters, numbers, underscores and dashes.',
    ]) }}

    @include('includes.components.error', ['field' => 'slug'])

    @if(!empty($category))
        {!! Form::submit('Edit', [
            'class' => 'btn btn-primary btn-block'
        ]) !!}
    @else
        {!! Form::submit('Create', [
            'class' => 'btn btn-primary btn-block'
        ]) !!}
    @endif

{!! Form::close() !!}