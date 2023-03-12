@extends('app')

@section('content')
    <div class="container">
        <div class="container">
            <div class="tile">
                <div class="tile-body">
                    <h3>Excluir usuário</h3>
                    <form action="{{ url('user/delete', $user->id) }}" method="post">
                        @csrf
                        <div class="alert alert-warning" role="alert">
                            Você deseja excluir o registro de {{ $user->name }}?
                        </div>
                        <div class="d-flex justify-content-center justify-content-between pt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Confirmar</button>
                            <a href="{{ url('/users') }}" class="btn btn-danger btn-lg">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
