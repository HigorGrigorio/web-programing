@if (session()->get('success'))
    <div class="alert alert-success alert-dismissible mt-2 fade show" role="alert">
        <i class="fa fa-check-circle mr-3"></i>
        <span>
            <strong>
                {{ session()->get('success') }}
            </strong>
        </span>
        <button type="button" data-dismiss="alert" class="close" aria-label="close">
            <span>
                <i class="fa fa-times"></i>
            </span>
        </button>
    </div>
@endif

@if (session()->get('fail'))
    <div class="alert alert-danger alert-dismissible mt-2 fade show" role="alert">
        <i class="fa fa-trash mr-3"></i>
        <span>
            <strong>
                {{ session()->get('fail') }}
            </strong>
        </span>
        <button type="button" data-dismiss="alert" class="close" aria-label="close">
            <span>
                <i class="fa fa-times"></i>
            </span>
        </button>
    </div>
@endif
