@extends('app')

@section('content')
    <div class="container">
        <div class="container">
            <div class="tile">
                <x-alert />
                <div class="tile-body d-flex flex-row">
                    <a href="{{ url('user/new') }}" class="btn btn-primary mb-3 btn-md">
                        <i class="fa fa-plus-circle"></i>
                        <span class="ml-2 flex-nowrap">Inserir usuário</span>
                    </a>
                </div>
                @if (count($users) === 0)
                    <div class="container px-0" id="table-message">
                        <div class="alert alert-warning" role="alert">
                            Nenhum usuário encontrado.
                        </div>
                    </div>
                @else
                    <table id="table" class="table table-striped table-bordered table-sm">
                        <thead>
                            <tr role="row">
                                <th class="th-sm">
                                    Id
                                </th>
                                <th class="th-sm">
                                    Nome
                                </th>
                                <th class="th-sm">
                                    Email
                                </th>
                                <th class="th-sm">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td class="d-flex justify-content-center">
                                        <div class="btn-group">
                                            <a href="{{ url('user/edit', $user->id) }}" class="btn btn-primary btn-sm">
                                                <i class="fa fa-pencil m-0" style="min-width: 2rem"></i>
                                            </a>
                                            <a href="{{ url('user/delete', $user->id) }}" class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash m-0" style="min-width: 2rem"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $users->links() }}
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#table').DataTable({
            responsive: true,
            autoWidth: false,
            paging: false,
            searching: true,
            info: false,
            order: [
                [0, "asc"]
            ],
            columnDefs: [{
                orderable: false,
                targets: [3]
            }],
        });
    </script>
@endpush
