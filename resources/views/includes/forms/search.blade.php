{!! Form::open([
    'route' => 'search',
    'method' => 'GET',
    'class' => 'form-search',
]) !!}

    {{ Form::label('phrase', 'Phrase') }}
    {{ Form::text('phrase', $initialPhrase ?? '', [
        'class' => 'form-control mb-3',
        'placeholder' => 'some phrase',
        'maxlength' => '255',
        'data-parsley-maxlength' => '255',
    ]) }}

    {{ Form::label('writer', 'Writer') }}
    {{ Form::select('writer', (function ($writers) {

        $result[null] = 'Anyone';

        foreach ($writers as $writer) {
            $result[$writer->id] = $writer->name . ' ' . $writer->surname;
        }

        return $result;
    })($writers), $initialWriter ?? '', [
        'class' => 'form-control mb-3',
        'style' => 'font-family: Nunito !important',
    ]) }}

    {{ Form::label('categories[]', 'Categories') }}
    {{ Form::select('categories[]', (function ($categories) {
        $result = [];
        
        foreach ($categories as $category) {
            $result[$category->id] = $category->name;
        }

        return $result;
    })($categories), $initialCategories ?? '', [
        'class' => 'form-control mb-3 selectpicker',
        'data-live-search' => 'true',
        'multiple' => '',
        'style' => 'font-family: Nunito !important;',
    ]) }}

    <!-- <label>Categories</label> -->

    <noscript>
        <select class="form-control mb-3 w-100" style="font-family: Nunito !important;" name="categories[]">
            <option selected>Select Categories</option>
            @if(count($categories))
                @foreach($categories as $category)
                <option value="{{ $category->id }}">
                    {{ $category->name }}
                </option>
                @endforeach
            @endif
        </select>
    </noscript>

    {{ Form::label('tags[]', 'Tags') }}
    {{ Form::select('tags[]', (function ($tags) {
            $result = [];
            
            foreach ($tags as $tag) {
                $result[$tag->id] = $tag->name;
            }

            return $result;
        })($tags), $initialTags ?? '', [
            'class' => 'form-control mb-3 selectpicker',
            'data-live-search' => 'true',
            'multiple' => '',
            'style' => 'font-family: Nunito !important;',
    ]) }}

    <noscript>
        <select class="form-control mb-3" data-live-search="true" multiple="" style="font-family: Nunito !important;" name="tags[]">
            <option selected>Select Tags*</option>
            @if(count($tags))
                @foreach($tags as $tag)
                <option value="{{ $tag->id }}">
                    {{ $tag->name }}
                </option>
                @endforeach
            @endif
        </select>
    </noscript>

    {!! Form::submit('Search', [
        'class' => 'btn btn-primary btn-block'
    ]) !!}

{!! Form::close() !!}