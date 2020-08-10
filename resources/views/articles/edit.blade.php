@extends('layouts.app')

@section('title', ' | Edit Article: ' . e($oldArticleTitle))

@section('links')
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 col-md-10 col-xl-8 offset-md-1 offset-xl-2">
            <a href="{{ route('articles.index') }}" class="btn btn-sm btn-outline-muted mb-4">
                Go Back
            </a>

            <h2>Edit Article: {{ $article->title }}</h2>

            {!! Form::open([
                'route' => ['articles.update', $article->id],
                'method' => 'PATCH',
                'class' => 'form-edit',
                'files' => true,
            ]) !!}

                {{ Form::label('title', 'Title') }}
                {{ Form::text('title', $article->title, [
                    'class' => 'form-control mb-3',
                    'required' => '',
                    'minlength' => '15',
                    'maxlength' => '255',
                    'pattern' => '[\w\-\+\#\$\%\^\&\@\!\(\)\|\=\,\.\<\>\/\?\" ]+',
                    'data-validator' => 'true',
                    'data-validator-required' => 'true',
                    'data-validator-minlength' => '15',
                    'data-validator-maxlength' => '255',
                    'data-validator-pattern' => '^[\w\-\+\#\$\%\^\&\@\!\(\)\|\=\,\.\<\>\/\?\" ]+$',
                    'data-validator-required-message' => 'Title is required.',
                    'data-validator-minlength-message' => 'Title must be equeal or longer than 15 characters long.',
                    'data-validator-maxlength-message' => 'Title must be equeal or shorter than 255 characters long.',
                    'data-validator-pattern-message' => 'Title contains forbidden characters.',
                ]) }}

                {{ Form::label('slug', 'Slug') }}
                {{ Form::text('slug', $article->slug, [
                    'class' => 'form-control mb-3',
                    'required' => '',
                    'minlength' => '15',
                    'maxlength' => '255',
                    'pattern' => '[0-9a-zA-Z_\-]+',
                    'data-validator' => 'true',
                    'data-validator-required' => 'true',
                    'data-validator-minlength' => '15',
                    'data-validator-maxlength' => '255',
                    'data-validator-pattern' => '^[0-9a-zA-Z_\-]+$',
                    'data-validator-required-message' => 'Slug is required.',
                    'data-validator-minlength-message' => 'Slug must be equeal or longer than 15 characters long.',
                    'data-validator-maxlength-message' => 'Slug must be equeal or shorter than 255 characters long.',
                    'data-validator-pattern-message' => 'Slug may contain only letters, numbers, underscores and dashes.',
                ]) }}

                @if(count($categories))
                {{ Form::label('categories[]', 'Categories') }}
                {{ Form::select('categories[]', (function ($categories) {
                        $result = [];
                        
                        foreach ($categories as $category) {
                            $result[$category->id] = $category->name;
                        }

                        return $result;
                    })($categories), $article->categories, [
                        'class' => 'form-control mb-3 selectpicker',
                        'style' => 'font-family: Nunito !important;',
                        'multiple' => '',
                        'data-live-search' => 'true',
                        'required' => '',
                        'data-validator' => 'true',
                        'data-validator-required' => 'true',
                        'data-validator-required-message' => 'Select one or more categories.',
                ]) }}
                @endif

                <noscript>
                    <label>
                        <select class="form-control mb-3" data-live-search="true" multiple style="font-family: Nunito !important;" name="categories[]">
                            <option selected>Select Categories</option>
                            @if(count($categories))
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" @if($article->categories()->get()->contains($category)) selected @endif>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            @endif
                        </select>
                    </label>
                </noscript>

                @if(count($tags))
                {{ Form::label('tags[]', 'Tags*') }}
                {{ Form::select('tags[]', (function ($tags) {
                        $result = [];
                        
                        foreach ($tags as $tag) {
                            $result[$tag->id] = $tag->name;
                        }

                        return $result;
                    })($tags), $article->tags, [
                        'class' => 'form-control mb-3 selectpicker',
                        'data-live-search' => 'true',
                        'multiple' => '',
                        'style' => 'font-family: Nunito !important;'
                ]) }}
                @endif

                <noscript>
                    <label>
                        <select class="form-control mb-3" data-live-search="true" multiple style="font-family: Nunito !important;" name="tags[]">
                            <option selected>Select Tags*</option>
                            @if(count($tags))
                                @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" @if($article->tags()->get()->contains($tag)) selected @endif>
                                    {{ $tag->name }}
                                </option>
                                @endforeach
                            @endif
                        </select>
                    </label>
                </noscript>

                {{ Form::label('cover_image', 'Cover Image') }}
                {{ Form::file('cover_image', [
                    'class' => 'form-control mb-3'
                ]) }}

                {{ Form::label('description', 'Image Description*') }}
                {{ Form::text('description', '', [
                    'class' => 'form-control mb-3',
                    'maxlength' => '255',
                    'pattern' => '[\w\-\+\#\$\%\^\&\@\!\(\)\|\=\,\.\<\>\/\?\" ]+',
                    'data-validator' => 'true',
                    'data-validator-maxlength' => '255',
                    'data-validator-pattern' => '^[\w\-\+\#\$\%\^\&\@\!\(\)\|\=\,\.\<\>\/\?\" ]+$',
                    'data-validator-maxlength-message' => 'Image dascription must be equeal or shorter than 255 characters long.',
                    'data-validator-pattern-message' => 'Image description contains forbidden characters.',
                ]) }}

                {{ Form::label('body', 'Body') }}
                {{ Form::textarea('body', $article->body, [
                    'id' => 'article-ckeditor',
                    'class' => 'form-control mb-3',
                    'rows' => '12',
                    'style' => 'resize: none;',
                    'required' => '',
                    'minlength' => '15',
                    'pattern' => '[\w\-\+\#\$\%\^\&\@\!\(\)\|\=\,\.\<\>\/\?\" ]+',
                    'data-validator' => 'true',
                    'data-validator-required' => 'true',
                    'data-validator-minlength' => '15',
                    'data-validator-pattern' => '^[\w\-\+\#\$\%\^\&\@\!\(\)\|\=\,\.\<\>\/\?\" ]+$',
                    'data-validator-required-message' => 'Article body is required.',
                    'data-validator-minlength-message' => 'Article body must be equeal or longer than 15 characters long.',
                    'data-validator-pattern-message' => 'Article body contains forbidden characters.',
                ]) }}

                {!! Form::submit('Edit', [
                    'class' => 'btn btn-primary btn-block mt-3'
                ]) !!}

            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>

    <script type="text/javascript">
        CKEDITOR.replace('article-ckeditor');
    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#description').addClass('d-none')
            $('label[for="description"]').addClass('d-none')

            $('#cover_image').change(function (e) {
                if ($(this).val() === '') {
                    $('#description').addClass('d-none')
                    $('label[for="description"]').addClass('d-none')
                
                } else {
                    $('#description').removeClass('d-none')
                    $('label[for="description"]').removeClass('d-none')
                }
            })
        })
    </script>
@endsection