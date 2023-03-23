<form action="{{ $action }}" method="{{ $method }}" id="{{ $id }}" enctype="multipart/form-data">
    @csrf
    @foreach ($fields as $field)
        <div class="form-group" @error($field['name']) invalid @enderror>
            <label for="{{ $field['name'] }}" class="control-label">{{ $field['label'] }}</label>
            <input type="text" class="form-control
                    @error($field['name']) is-invalid @enderror"
                name="{{ $field['name'] }}" placeholder="{{ $field['placeholder'] }}" value="{{ $field['value'] }}">
            @error($field['name'])
                @foreach ($errors->get($field['name']) as $error)
                    <span class="invalid-feedback" role="alert">
                        {{ $error }}
                    </span>
                @endforeach
            @enderror
        </div>
    @endforeach
    <div class="d-flex justify-content-center justify-content-between pt-3">
        <button type="submit" class="btn btn-primary btn-lg">{{ $submit['label'] }}</button>
        <a id="previous" href="{{ $previous['url'] }}" class="btn btn-danger btn-lg">{{ $previous['label'] }}</a>
    </div>
    <div class="col-md-12 pt-5 px-0" id="notify-container">
    </div>
</form>
