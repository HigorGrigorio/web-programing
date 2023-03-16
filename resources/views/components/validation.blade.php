@if ($errors->any())
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger alert-dismissible mt-2 fade show" role="alert">
            <i class="fa fa-exclamation-circle mr-3"></i>
            <span>
                <strong>
                    {{ $error }}
                </strong>
            </span>
            <br>
            <button type="button" data-dismiss="alert" class="close" aria-label="close">
                <span>
                    <i class="fa fa-times"></i>
                </span>
            </button>
        </div>
    @endforeach
@endif
