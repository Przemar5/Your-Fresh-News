@extends('layouts.app')

@section('title', ' | ratings')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-8">
            <h2>ratings</h2>

            <div class="table-responsive @if(!count($ratings)) d-none @endif" id="table">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Title</th>
                            <th>Image Path</th>
                            @if(Auth::user())
                            <th class="d-block float-right text-left" style="width: 13.75rem;">Action</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @if(count($ratings))
                        	@foreach($ratings as $rating)
                            <tr>
                                <td>
                                    <a href="{{  route('ratings.show', $rating->slug)  }}">
                                    	{{  $rating->id  }}
                                    </a>
                                </td>
                                <td data-attrname="rating-name">
                                    {{  $rating->name  }}
                                </td>
                                <td data-attrname="rating-slug">
                                    {{  $rating->title  }}
                                </td>
                                <td data-attrname="rating-slug">
                                    {{  $rating->image_path  }}
                                </td>
                                @if(Auth::user())
                                <td class="d-flex justify-content-between d-block float-right" style="max-width: 14rem;">
                                	{!! Form::open([
    				                    'route' => ['ratings.edit', $rating->id],
    				                    'method' => 'GET',
    				                    'class' => 'form-edit pull-right mr-3',
                                        'data-route' => route('ratings.update', $rating->id)
    					            ]) !!}

    					                {!! Form::button('<i class="fas fa-pencil-alt"></i>&nbsp;Edit', [
    					                	'type' => 'submit',
    					                    'class' => 'btn btn-sm btn-outline-primary'
    					                ]) !!}

    					            {!! Form::close() !!}

                                	{!! Form::open([
    				                    'route' => ['ratings.destroy', $rating->id],
    				                    'method' => 'DELETE',
    				                    'class' => 'form-delete pull-right'
    					            ]) !!}

    					                {!! Form::button('<i class="fas fa-trash-alt"></i>&nbsp;Delete', [
    					                	'type' => 'submit',
    					                    'class' => 'btn btn-sm btn-outline-danger'
    					                ]) !!}

    					            {!! Form::close() !!}
                                </td>
                                @endif
                            </tr>
                        	@endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        	
            <p class="no-results @if(count($ratings)) d-none @endif">
                No ratings found
            </p>
        </div>

        @if(Auth::user())
        <div class="col-sm-4 mt-4 mt-sm-0">
            <h2>New rating</h2>

            {!! Form::open([
                'route' => 'ratings.store',
                'method' => 'POST',
                'class' => 'form-create'
            ]) !!}

                {{ Form::label('type', 'Type') }}
                {{ Form::text('type', '', [
                    'class' => 'form-control mb-3',
                    'required' => '',
                    'maxlength' => '255',
                    'pattern' => '[\w\-\+\#\$\%\^\&\@\!\(\)\|\=\,\.\<\>\/\?\" ]+',
                    'data-validator' => 'true',
                    'data-validator-required' => 'true',
                    'data-validator-maxlength' => '255',
                    'data-validator-pattern' => '^[\w\-\+\#\$\%\^\&\@\!\(\)\|\=\,\.\<\>\/\?\" ]+$',
                    'data-validator-required-message' => 'Rating type is required.',
                    'data-validator-maxlength-message' => 'Rating type must be equal or shorter than 255 characters long.',
                    'data-validator-pattern-message' => 'Rating type contains forbidden characters.',
                ]) }}

                {{ Form::label('title', 'Title') }}
                {{ Form::text('title', '', [
                    'class' => 'form-control mb-3',
                    'maxlength' => '255',
                    'pattern' => '[0-9a-zA-Z_\-]+',
                    'data-validator' => 'true',
                    'data-validator-maxlength' => '255',
                    'data-validator-pattern' => '[0-9a-zA-Z_\-]+',
                    'data-validator-maxlength-message' => 'Rating title must be equal or shorter than 255 characters long.',
                    'data-validator-pattern-message' => 'Rating title may contain only letters, numbers, underscores and dashes.',
                ]) }}

                {{ Form::label('image', 'Image') }}
                {{ Form::file('image', [
                    'class' => 'form-control mb-3',
                    'required' => '',
                    'data-validator' => 'true',
                    'data-validator-required' => 'true',
                    'data-validator-required-message' => 'Image is required.',
                ]) }}

                {!! Form::submit('Create', [
                    'class' => 'btn btn-primary btn-block'
                ]) !!}

            {!! Form::close() !!}
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ URL::asset('js/ratings/index.js') }}"></script>
@endsection