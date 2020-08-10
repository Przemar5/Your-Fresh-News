@extends('layouts.app')

@section('title', ' | Categories')

@section('content')
<div class="container-fluid container-md">
    <div class="row">
        <div class="col-12 @if(Auth::user() && Auth::user()->is('admin')) col-lg-8 @else col-sm-10 offset-sm-1 @endif">
            <h2>Categories</h2>

            <div class="table-responsive @if(!count($categories)) d-none @endif" id="table">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Slug</th>
                            @if(Auth::user() && Auth::user()->is('admin'))
                            <th class="d-block float-right text-left" style="width: 13.75rem;">Action</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @if(count($categories))
                        	@foreach($categories as $category)
                            <tr>
                                <td>
                                    <a href="{{  route('categories.show', $category->slug)  }}">
                                    	{{  $category->id  }}
                                    </a>
                                </td>
                                <td data-attrname="category-name">
                                    {{  $category->name  }}
                                </td>
                                <td data-attrname="category-slug">
                                    {{  $category->slug  }}
                                </td>
                                @if(Auth::user() && Auth::user()->is('admin'))
                                <td class="d-flex justify-content-between d-block float-right" style="max-width: 14rem;">
                                	{!! Form::open([
    				                    'route' => ['categories.edit', $category->slug],
    				                    'method' => 'GET',
    				                    'class' => 'form-edit pull-right mr-3',
                                        'data-route' => route('categories.update', $category->id)
    					            ]) !!}

    					                {!! Form::button('<i class="fas fa-pencil-alt"></i>&nbsp;Edit', [
    					                	'type' => 'submit',
    					                    'class' => 'btn btn-sm btn-outline-primary'
    					                ]) !!}

    					            {!! Form::close() !!}

                                	{!! Form::open([
    				                    'route' => ['categories.destroy', $category->id],
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
        	
            <p class="no-results @if(count($categories)) d-none @endif">
                No categories found
            </p>

            {!! $categories->render() !!}
        </div>

        @if(Auth::user() && Auth::user()->is('admin'))
        <div class="col-lg-4 mt-3 mt-lg-0">
            <h2>New Category</h2>

            @include('includes.forms.category', ['category' => null])
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ URL::asset('js/categories/index.js') }}"></script>
@endsection