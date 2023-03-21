@extends('app')

@section('content')
    <div class="container">
        <div class="container">
            <div class="tile">
                <x-alert />
                <div class="tile-body d-flex flex-row">
                    <div class="d-flex align-items-center col-md-10" style="padding: 0;">
                        <form id="search" class="input-group mb-3" action="{{ url('user/search') }}" method="GET">
                            @csrf
                            <input name="offset" type="number" class="form-control" placeholder="Offset"
                                aria-label="Offset" aria-describedby="basic-addon2" style="max-width: 8rem;">
                            <input name="limit" type="number" class="form-control" placeholder="Limit" aria-label="Limit"
                                aria-describedby="basic-addon2" style="max-width: 8rem;">
                            <input name="search" type="text" class="form-control" placeholder="Search for..."
                                aria-label="Search for..." aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-warning" type="submit">Button</button>
                            </div>
                        </form>
                    </div>
                    <div class="d-flex flex-row-reverse" style="width: 100%">
                        <a href="{{ url('user/new') }}" class="btn btn-warning mb-3 btn-md">
                            <i class="fa fa-plus-circle"></i>
                            <span class="ml-2 flex-nowrap">Inserir usuário</span>
                        </a>
                    </div>
                </div>
                <table id="table" class="table table-striped table-bordered table-hover">
                    <tr>
                        <th>Id</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Ações</th>
                    </tr>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td class="d-flex justify-content-center">
                                    <a href="{{ url('user/edit', $user->id) }}" class="btn btn-primary btn-sm mr-2">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a href="{{ url('user/delete', $user->id) }}" class="btn btn-danger btn-sm ml-2">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="container" id="table-message">
                    @if (count($users) === 0)
                        <div class="alert alert-warning" role="alert">
                            Nenhum usuário encontrado.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
