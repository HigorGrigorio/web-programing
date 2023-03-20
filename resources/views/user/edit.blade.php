@extends('app')

@section('content')
    <div class="container">
        <div class="container">
            <div class="tile">
                <div class="tile-body">
                    @include('user.__form', [
                        'context' => 'edit',
                        'action' => url('/user/update', $user->id),
                    ])
                </div>
            </div>
        </div>
    </div>
@endsection
