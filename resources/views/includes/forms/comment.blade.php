@if(empty($comment))
{!! Form::open([
    'route' => 'comments.store',
    'method' => 'POST',
    'class' => (isset($class) && !empty($class)) ? $class : 'form-comment-create',
]) !!}
@else
{!! Form::open([
    'route' => ['comments.update', $comment->id],
    'method' => 'PATCH',
    'class' => (isset($class) && !empty($class)) ? $class : 'form-comment-edit',
]) !!}
@endif

    @if(empty($comment))
    {{ Form::hidden('article', $articleId) }}
    @else
    {{ Form::hidden('article', $comment->article->id) }}
    @endif

    @if(!empty($comment) && !empty($comment->parent_comment_id))
    {{ Form::hidden('parent_comment_id', $comment->parent_comment_id) }}
    @elseif(!empty($parentCommentId))
    {{ Form::hidden('parent_comment_id', $parentCommentId) }}
    @endif

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

    @include('includes.components.error', ['field' => 'title'])

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

    @include('includes.components.error', ['field' => 'body'])

    @if(empty($comment))
    {!! Form::submit('Create', [
        'class' => 'btn btn-primary btn-block mt-3'
    ]) !!}
    @else
    {!! Form::submit('Update', [
        'class' => 'btn btn-primary btn-block mt-3'
    ]) !!}
    @endif

{!! Form::close() !!}