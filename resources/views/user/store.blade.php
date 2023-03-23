@extends('app')

@section('content')
    <div class="container">
        <div class="container">
            <div class="tile">
                <div class="tile-body">
                    <x-validation />
                    @include('user.__form', [
                        'context' => 'store',
                        'action' => url('/user'),
                    ])
                </div>
            </div>
        </div>
    </div>
@endsection
