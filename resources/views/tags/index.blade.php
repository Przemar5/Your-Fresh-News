@extends('layouts.app')

@section('title', ' | Tags')

@section('content')
<div class="container">
    <div class="row">
        <div class="@if(Auth::user() && Auth::user()->is('admin')) col-md-8 @else col-sm-10 offset-sm-1 @endif">
            <h2>Tags</h2>

            <div class="table-responsive @if(!count($tags)) d-none @endif" id="table">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            @if(Auth::user() && Auth::user()->is('admin'))
                            <th class="d-block float-right text-left" style="width: 13.75rem;">Action</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @if(count($tags))
                        	@foreach($tags as $tag)
                            <tr>
                                <td>
                                    <a href="{{  route('tags.show', $tag->id)  }}">
                                    	{{  $tag->id  }}
                                    </a>
                                </td>
                                <td data-attrname="tag-name">
                                    {{  $tag->name  }}
                                </td>
                                @if(Auth::user() && Auth::user()->is('admin'))
                                <td class="d-flex justify-content-between d-block float-right" style="max-width: 14rem;">
                                	{!! Form::open([
    				                    'route' => ['tags.edit', $tag->id],
    				                    'method' => 'GET',
    				                    'class' => 'form-edit pull-right mr-3',
                                        'data-route' => route('tags.update', $tag->id)
    					            ]) !!}

    					                {!! Form::button('<i class="fas fa-pencil-alt"></i>&nbsp;Edit', [
    					                	'type' => 'submit',
    					                    'class' => 'btn btn-sm btn-outline-primary'
    					                ]) !!}

    					            {!! Form::close() !!}

                                	{!! Form::open([
    				                    'route' => ['tags.destroy', $tag->id],
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
        	
            <p class="no-results @if(count($tags)) d-none @endif">
                No tags found
            </p>

            {!! $tags->render() !!}
        </div>

        @if(Auth::user() && Auth::user()->is('admin'))
        <div class="col-md-4 mt-4 mt-md-0">
            <h2>New Tag</h2>

            @include('includes.forms.tag', ['tag' => null])
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ URL::asset('js/tags/index.js') }}"></script>
@endsection