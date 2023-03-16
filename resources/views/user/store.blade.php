@extends('app')

@section('content')
    <div class="container">
        <div class="container">
            <div class="tile">
                <div class="tile-body">
                    <div class="col-md-8">
                        <x-validation />
                        <form action="{{ url('user') }}" method="post">
                            @csrf
                            <div class="form-group" @error('name') invalid @enderror>
                                <label for="name" class="control-label">Nome</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" placeholder="Nome" value="">
                                @error('name')
                                    @foreach ($errors->get('name') as $error)
                                        <span class="invalid-feedback" role="alert">
                                            {{ $error }}
                                        </span>
                                    @endforeach
                                @enderror
                            </div>
                            <div class="form-group" @error('email') invalid @enderror>
                                <label for="email" class="control-label">Email</label>
                                <input type="text" class="form-control @error('email') is-invalid @enderror"
                                    name="email" placeholder="Email" value="">
                                @error('email')
                                    @foreach ($errors->get('email') as $error)
                                        <span class="invalid-feedback" role="alert">
                                            {{ $error }}
                                        </span>
                                    @endforeach
                                @enderror
                            </div>
                            <div class="d-flex justify-content-center justify-content-between pt-4">
                                <button type="submit" class="btn btn-primary btn-lg">Inserir</button>
                                <a href="{{ url('/users') }}" class="btn btn-danger btn-lg">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
