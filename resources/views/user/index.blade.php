@extends('app')

@section('content')
    <div class="container">
        <div class="container">
            <div class="tile">
                <div class="tile-body d-flex flex-row">
                    <x-alert />
                    <div class="d-flex align-items-center col-md-10" style="padding: 0;">
                        <form class="input-group mb-3" action="user/search" method="POST">
                            <input type="text" class="form-control" placeholder="Search for..."
                                aria-label="Recipient's username" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-success" type="button">Button</button>
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
                <table class="table table-striped table-bordered table-hover">
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
            </div>
        </div>
    </div>
@endsection
