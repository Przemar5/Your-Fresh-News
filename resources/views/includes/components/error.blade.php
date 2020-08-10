@if($errors->any() && !empty($errors->first($field)))
    <div class="alert alert-danger">
        {{ $errors->first($field) }}
    </div>
@endif