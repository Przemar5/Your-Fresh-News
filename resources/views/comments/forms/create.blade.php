{!! Form::open([
    'route' => 'comments.store',
    'method' => 'POST',
    'class' => 'form-comment-create'
]) !!}

    {{ Form::hidden('article', $article->id) }}

    {{ Form::label('title', 'Title') }}
    {{ Form::text('title', $comment->title ?? '', [
        'class' => 'form-control mb-3',
        'required' => '',
        'minlength' => '5',
        'maxlength' => '255',
        'pattern' => '[\w\-\+\#\$\%\^\&\@\!\(\)\|\=\,\.\<\>\/\?\" ]+',
        'data-validator' => 'true',
        'data-validator-required' => 'true',
        'data-validator-minlength' => '5',
        'data-validator-minlength' => '255',
        'data-validator-pattern' => '^[\w\-\+\#\$\%\^\&\@\!\(\)\|\=\,\.\<\>\/\?\" ]+$',
        'data-validator-required-message' => 'Comment title is required.',
        'data-validator-minlength-message' => 'Comment title must be equal or longer than 5 characters long.',
        'data-validator-maxlength-message' => 'Comment title must be equal or less than 255 characters long.',
        'data-validator-pattern-message' => 'Comment title contains forbidden characters.',
    ]) }}

    {{ Form::label('nick', 'Nick') }}
    {{ Form::text('nick', $comment->nick ?? '', [
        'class' => 'form-control mb-3',
        'required' => '',
        'minlength' => '5',
        'maxlength' => '255',
        'pattern' => '[\w\-\+\#\$\%\^\&\@\!\(\)\|\=\,\.\<\>\/\?\" ]+',
        'data-validator' => 'true',
        'data-validator-required' => 'true',
        'data-validator-minlength' => '5',
        'data-validator-minlength' => '255',
        'data-validator-pattern' => '^[\w\-\+\#\$\%\^\&\@\!\(\)\|\=\,\.\<\>\/\?\" ]+$',
        'data-validator-required-message' => 'Comment nick is required.',
        'data-validator-minlength-message' => 'Comment nick must be equal or longer than 5 characters long.',
        'data-validator-maxlength-message' => 'Comment nick must be equal or less than 255 characters long.',
        'data-validator-pattern-message' => 'Comment nick contains forbidden characters.',
    ]) }}

    {{ Form::label('body', 'Body') }}
    {{ Form::textarea('body', $comment->body ?? '', [
        'class' => 'form-control mb-3',
        'rows' => '5',
        'style' => 'resize: none;',
        'required' => '',
        'maxlength' => '800',
        'pattern' => '[\w\-\+\#\$\%\^\&\@\!\(\)\|\=\,\.\<\>\/\?\" ]+',
        'data-validator' => 'true',
        'data-validator-required' => 'true',
        'data-validator-maxlength' => '800',
        'data-validator-pattern' => '^[\w\-\+\#\$\%\^\&\@\!\(\)\|\=\,\.\<\>\/\?\" ]+$',
        'data-validator-required-message' => 'Comment body is required.',
        'data-validator-maxlength-message' => 'Comment body must be equal or less than 800 characters long.',
        'data-validator-pattern-message' => 'Comment body contains forbidden characters.',
    ]) }}

    {!! Form::submit('Create', [
        'class' => 'btn btn-primary btn-block mt-3'
    ]) !!}

{!! Form::close() !!}